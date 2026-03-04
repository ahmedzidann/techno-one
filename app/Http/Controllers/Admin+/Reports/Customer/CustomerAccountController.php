<?php

namespace App\Http\Controllers\Admin\Reports\Customer;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerAccountController extends Controller
{
    private float $balanace = 0;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->getUnionQuery($request);
            $currentBalance = 0;
            if ($request->client_id) {
                $clientPreviousBalance = Client::where('id', $request->client_id)->first()->previous_indebtedness;
                $currentBalance += $clientPreviousBalance;
            }
            if ($request->client_id && ($request->fromDate || $request->toDate)) {
                $initialBalance = $this->getInitialBalance($request);
                $currentBalance += $initialBalance;
            }
            return DataTables::of($query)
                ->addColumn('type_label', function ($row) {
                    return $this->getTypeLabel($row->type);
                })
                ->addColumn('balance', function ($row) use (&$currentBalance) {
                    $currentBalance += floatval($row->debt - $row->credit);
                    return $currentBalance;
                })
                ->editColumn('date', function ($row) {
                    return $row->date;
                })
                ->with('previous', $currentBalance)
                ->make(true);
        }

        return view('Admin.reports.accountStatement.clientAccountStatement.customer');
    }

    private function getUnionQuery($request)
    {
        $sales = DB::table('sales')
            ->where('status', 'complete')
            ->when($request->fromDate, function ($query) use ($request) {
                return $query->whereDate('sales_date', '>=', $request->fromDate);
            })
            ->when($request->toDate, function ($query) use ($request) {
                return $query->whereDate('sales_date', '<=', $request->toDate);
            })
            ->select(
                'sales_date as date',
                DB::raw('0 as credit'),
                'paid',
                DB::raw("'sales' as type"),
                'total as debt',
                'total as total_price',
                DB::raw('0 as client_payment_setting_id'),
                DB::raw('created_at as created_at')
            )
            ->when($request->client_id, function ($query) use ($request) {
                return $query->where('client_id', $request->client_id);
            });

        $headBackSales = DB::table('head_back_sales')
            ->when($request->fromDate, function ($query) use ($request) {
                return $query->whereDate('date', '>=', $request->fromDate);
            })
            ->when($request->toDate, function ($query) use ($request) {
                return $query->whereDate('date', '<=', $request->toDate);
            })
            ->select(
                'sales_date as date',
                'total as credit',
                'paid',
                DB::raw("'headBackSales' as type"),
                DB::raw('0 as debt'),
                'total as total_price',
                DB::raw('0 as client_payment_setting_id'),
                DB::raw('created_at as created_at')
            )
            ->when($request->client_id, function ($query) use ($request) {
                return $query->where('client_id', $request->client_id);
            });

        $esalat = DB::table('esalats')
            ->when($request->fromDate, function ($query) use ($request) {
                return $query->whereDate('date_esal', '>=', $request->fromDate);
            })
            ->when($request->toDate, function ($query) use ($request) {
                return $query->whereDate('date_esal', '<=', $request->toDate);
            })
            ->select(
                'date_esal as date',
                'paid as credit',
                'paid',
                DB::raw("'esalat' as type"),
                DB::raw('0 as debt'),
                'paid as total_price',
                'client_payment_setting_id',
                DB::raw('created_at as created_at')
            )
            ->when($request->client_id, function ($query) use ($request) {
                return $query->where('client_id', $request->client_id);
            });

        $client_adjustments = DB::table('client_adjustments')
            ->when($request->fromDate, function ($query) use ($request) {
                return $query->whereDate('date', '>=', $request->fromDate);
            })
            ->when($request->toDate, function ($query) use ($request) {
                return $query->whereDate('date', '<=', $request->toDate);
            })
            ->select(
                'date as date',
                DB::raw('CASE WHEN type = 2 THEN value ELSE 0 END as credit'), // Second case
                DB::raw('0 as paid'),
                DB::raw("'adjustment' as type"),
                DB::raw('CASE WHEN type = 1 THEN value ELSE 0 END as debt'), // First case
                DB::raw('0 as total_price'),
                DB::raw('NULL as client_payment_setting_id'),
                DB::raw('created_at as created_at')
            )
            ->when($request->client_id, function ($query) use ($request) {
                return $query->where('client_id', $request->client_id);
            });
        return $sales->unionAll($headBackSales)
            ->unionAll($esalat)
            ->unionAll($client_adjustments)
            ->orderBy('created_at', 'ASC');
    }

    private function getTypeLabel($type)
    {
        $labels = [
            'sales' => 'المبيعات',
            'headBackSales' => 'مرتجعات المبيعات',
            'esalat' => 'إيصالات السداد',
            'adjustment' => 'تسوية',
        ];

        return $labels[$type] ?? $type;
    }

    private function getInitialBalance($request)
    {
        $sales = DB::table('sales')
            ->where('status', 'complete')
            ->where('client_id', $request->client_id)
            ->whereDate('sales_date', '<', $request->fromDate)
            ->sum('total');

        $headBackSales = DB::table('head_back_sales')
            ->where('client_id', $request->client_id)
            ->whereDate('sales_date', '<', $request->fromDate)
            ->sum('total');

        $esalat = DB::table('esalats')
            ->where('client_id', $request->client_id)
            ->whereDate('date_esal', '<', $request->fromDate)
            ->sum('paid');

        $clientAdjustments = DB::table('client_adjustments')
            ->where('client_id', $request->client_id)
            ->whereDate('date', '<', $request->fromDate)
            ->select(
                DB::raw('SUM(CASE WHEN type = 1 THEN value ELSE 0 END) as total_debt'),
                DB::raw('SUM(CASE WHEN type = 2 THEN value ELSE 0 END) as total_credit')
            )
            ->first();

        $totalDebt = $sales + ($clientAdjustments->total_debt ?? 0);
        $totalCredit = $headBackSales + $esalat + ($clientAdjustments->total_credit ?? 0);

        return $totalDebt - $totalCredit;
    }
}
