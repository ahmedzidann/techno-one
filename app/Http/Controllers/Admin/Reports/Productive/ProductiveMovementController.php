<?php

namespace App\Http\Controllers\Admin\Reports\Productive;

use App\Enum\PurchaseStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\ProductBalance;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PurchasesDetails;
use Yajra\DataTables\Facades\DataTables;
use DateTime;

class ProductiveMovementController extends Controller
{

    protected $total = 0;
    public function index(Request $request)
    {
        $startDate = $request->input('fromDate') ? Carbon::parse($request->input('fromDate'))->startOfDay() : null;
        $endDate = $request->input('toDate') ? Carbon::parse($request->input('toDate'))->endOfDay() : null;
        $storage = $request->input('storage_id');
        $productive_id = $request->input('product_id');

        if ($request->ajax()) {

            $previousBalance = 0;
            if ($startDate != null) {
                $previousBalance = PurchasesDetails::whereHas('purchases', fn($q) => $q->where('status', PurchaseStatus::COMPLETED))
                    ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                    ->when($storage, fn($q) => $q->where('storage_id', $storage))
                    ->when($startDate, fn($q) => $q->where('created_at', '<', $startDate))
                    ->selectRaw('COALESCE(SUM(amount + COALESCE(bouns, 0)), 0) as total')
                    ->first()->total
                    + DB::table('head_back_sales_details')
                    ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                    ->when($storage, fn($q) => $q->where('storage_id', $storage))
                    ->when($startDate, fn($q) => $q->where('created_at', '<', $startDate))
                    ->selectRaw('COALESCE(SUM(amount + COALESCE(bouns, 0)), 0) as total')
                    ->first()->total
                    - DB::table('sales_details')
                    ->where('is_prepared', 1)
                    ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                    ->when($storage, fn($q) => $q->where('storage_id', $storage))
                    ->when($startDate, fn($q) => $q->where('created_at', '<', $startDate))
                    ->selectRaw('COALESCE(SUM(amount + COALESCE(bouns, 0)), 0) as total')
                    ->first()->total
                    - DB::table('head_back_purchases_details')
                    ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                    ->when($storage, fn($q) => $q->where('storage_id', $storage))
                    ->when($startDate, fn($q) => $q->where('created_at', '<', $startDate))
                    ->selectRaw('COALESCE(SUM(amount + COALESCE(bouns, 0)), 0) as total')
                    ->first()->total
                    - DB::table('destruction_details')
                    ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                    ->when($storage, fn($q) => $q->where('storage_id', $storage))
                    ->when($startDate, fn($q) => $q->where('created_at', '<', $startDate))
                    ->sum('amount')
                    + DB::table('product_adjustments')
                    ->when($productive_id, fn($q) => $q->where('product_id', $productive_id))
                    ->when($storage, fn($q) => $q->where('storage_id', $storage))
                    ->when($startDate, fn($q) => $q->where('created_at', '<', $startDate))
                    ->where('type', 1)
                    ->sum('amount')
                    - DB::table('product_adjustments')
                    ->when($productive_id, fn($q) => $q->where('product_id', $productive_id))
                    ->when($storage, fn($q) => $q->where('storage_id', $storage))
                    ->when($startDate, fn($q) => $q->where('created_at', '<', $startDate))
                    ->where('type', 2)
                    ->sum('amount');
            }

            $buildQuery = function ($tableName, $dateColumn, $type, $process) use ($startDate, $endDate, $storage, $productive_id) {

                return DB::table($tableName)->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                    ->when($storage, fn($q) => $q->where('storage_id', $storage))
                    ->selectRaw('SUM(amount) as total_amount,' . $dateColumn . '  as created_at, SUM(bouns) as bouns, ? as type , ? as process', [$type, $process])
                    ->when($startDate, fn($q) => $q->whereDate($dateColumn, '>=', $startDate))
                    ->when($endDate, fn($q) => $q->whereDate($dateColumn, '<=', $endDate))
                    ->groupBy('created_at');
            };

            $rasiedAyniSum = DB::table('rasied_ayni')
                ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                ->when($storage, fn($q) => $q->where('storage_id', $storage))
                ->sum('amount');

            $query = DB::table(DB::raw('(SELECT 1) as dummy'))
                ->selectRaw(
                    '? as total_amount, 0 as created_at, 0 as bouns, ? as type , ? as process',
                    [$rasiedAyniSum + $previousBalance, 'رصيد أول المدة', 1]
                )->unionAll(
                    DB::table('sales_details')->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                        ->when($storage, fn($q) => $q->where('storage_id', $storage))
                        ->where('is_prepared', 1)
                        ->selectRaw('SUM(amount) as total_amount, created_at as created_at, SUM(bouns) as bouns, ? as type , ? as process', ['مبيعات', 2])
                        ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                        ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
                        ->groupBy(DB::raw('created_at'))
                )->unionAll(
                    PurchasesDetails::whereHas('purchases', fn($q) => $q->where('status', PurchaseStatus::COMPLETED))->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                        ->when($storage, fn($q) => $q->where('storage_id', $storage))
                        ->selectRaw('SUM(amount) as total_amount, created_at as created_at, SUM(bouns) as bouns, ? as type , ? as process', ['مشتريات', 3])
                        ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                        ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
                        ->groupBy(DB::raw('created_at'))
                )
                ->unionAll($buildQuery('head_back_sales_details', 'created_at', 'مرتجع مبيعات', 4))
                ->unionAll($buildQuery('head_back_purchases_details', 'created_at', 'مرتجع مشتريات', 5))
                ->unionAll(
                    DB::table('destruction_details')
                        ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                        ->when($storage, fn($q) => $q->where('storage_id', $storage))
                        ->selectRaw('SUM(amount) as total_amount, created_at as created_at, 0 as bouns, ? as type, ? as process', ['اهلاك', 6])
                        ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                        ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
                        ->groupBy(DB::raw('created_at'))
                )
                ->unionAll(
                    DB::table('product_adjustments')
                        ->when($productive_id, fn($q) => $q->where('product_id', $productive_id))
                        ->when($storage, fn($q) => $q->where('storage_id', $storage))
                        ->where('type', 1)
                        ->selectRaw('SUM(amount) as total_amount, created_at as created_at, 0 as bouns, ? as type, ? as process', ['تسوية بالزيادة', 7])
                        ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                        ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
                        ->groupBy(DB::raw('created_at'))
                )
                ->unionAll(
                    DB::table('product_adjustments')
                        ->when($productive_id, fn($q) => $q->where('product_id', $productive_id))
                        ->when($storage, fn($q) => $q->where('storage_id', $storage))
                        ->where('type', 2)
                        ->selectRaw('SUM(amount) as total_amount, created_at as created_at, 0 as bouns, ? as type, ? as process', ['تسوية بالعجز', 8])
                        ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                        ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
                        ->groupBy(DB::raw('created_at'))
                );
            // Order the final result
            $query->orderBy('created_at', 'ASC');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('total', function ($row) {
                    if ($row->process == 1) {
                        $this->total += ($row->total_amount + $row->bouns);
                    } elseif ($row->process == 2) {
                        $this->total -= ($row->total_amount + $row->bouns);
                    } elseif ($row->process == 3) {
                        $this->total += ($row->total_amount + $row->bouns);
                    } elseif ($row->process == 4) {
                        $this->total += ($row->total_amount + $row->bouns);
                    } elseif ($row->process == 5) {
                        $this->total -= ($row->total_amount + $row->bouns);
                    } elseif ($row->process == 6) {
                        $this->total -= ($row->total_amount + $row->bouns);
                    } elseif ($row->process == 7) {
                        $this->total += ($row->total_amount);
                    } elseif ($row->process == 8) {
                        $this->total -= ($row->total_amount);
                    }
                    return $this->total;
                })

                ->editColumn('total_amount', function ($row) {
                    if ($row->bouns) {
                        return $row->total_amount + (int)$row->bouns . "($row->bouns)";
                    }

                    return  $row->total_amount;
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d');
                })
                ->escapeColumns([])
                ->make(true);
        }
        return view('Admin.reports.productive.index', $this->statistics($productive_id, $storage, $startDate, $endDate));
    }

    public function statistics($productive_id = null, $storage = null, $startDate = null, $endDate = null)
    {
        $rasied_ayni = DB::table('rasied_ayni')
            ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
            ->when($storage, fn($q) => $q->where('storage_id', $storage))
            ->sum('amount');
        $sales_details = DB::table('sales_details')
            ->selectRaw('SUM(amount) as total_amount, SUM(bouns) as total_bouns')
            ->where('is_prepared', 1)
            ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
            ->when($storage, fn($q) => $q->where('storage_id', $storage))
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->first();

        $purchases_details = PurchasesDetails::whereHas('purchases', fn($q) => $q->where('status', PurchaseStatus::COMPLETED))
            ->selectRaw('SUM(amount) as total_amount, SUM(bouns) as total_bouns')
            ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
            ->when($storage, fn($q) => $q->where('storage_id', $storage))
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->first();

        $head_back_sales_details = DB::table('head_back_sales_details')
            ->selectRaw('SUM(amount) as total_amount, SUM(bouns) as total_bouns')
            ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
            ->when($storage, fn($q) => $q->where('storage_id', $storage))
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->first();
        $head_back_purchases_details = DB::table('head_back_purchases_details')
            ->selectRaw('SUM(amount) as total_amount, SUM(bouns) as total_bouns')
            ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
            ->when($storage, fn($q) => $q->where('storage_id', $storage))
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->first();
        $destruction_details = DB::table('destruction_details')
            ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
            ->when($storage, fn($q) => $q->where('storage_id', $storage))
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->sum('amount');
        $incremental_adjustment = DB::table('product_adjustments')
            ->when($productive_id, fn($q) => $q->where('product_id', $productive_id))
            ->when($storage, fn($q) => $q->where('storage_id', $storage))
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->where('type', 1)
            ->sum(DB::raw('amount'));
        $deficit_adjustment = DB::table('product_adjustments')
            ->when($productive_id, fn($q) => $q->where('product_id', $productive_id))
            ->when($storage, fn($q) => $q->where('storage_id', $storage))
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->where('type', 2)
            ->sum(DB::raw('amount'));

        return [
            'sales' => $sales_details,
            'purchases' => $purchases_details,
            'hadback_sales' => $head_back_sales_details,
            'hadback_purchases' => $head_back_purchases_details,
            'rasied_ayni' => $rasied_ayni + $this->previousBalance($productive_id, $storage, $startDate),
            'destruction' => $destruction_details,
            'incremental_adjustment' => $incremental_adjustment,
            'deficit_adjustment' => $deficit_adjustment,
        ];
    }
    public function previousBalance($productive_id = null, $storage = null, $startDate = null)
    {
        if ($startDate) {

            $sales_details = DB::table('sales_details')
                ->selectRaw('SUM(amount) as total_amount, SUM(bouns) as total_bouns')
                ->where('is_prepared', 1)
                ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                ->when($storage, fn($q) => $q->where('storage_id', $storage))
                ->when($startDate, fn($q) => $q->where('created_at', '<=', $startDate))
                ->first();

            $purchases_details = DB::table('purchases_details')
                ->selectRaw('SUM(amount) as total_amount, SUM(bouns) as total_bouns')
                ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                ->when($storage, fn($q) => $q->where('storage_id', $storage))
                ->when($startDate, fn($q) => $q->where('created_at', '<=', $startDate))
                ->first();

            $head_back_sales_details = DB::table('head_back_sales_details')
                ->selectRaw('SUM(amount) as total_amount, SUM(bouns) as total_bouns')
                ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                ->when($storage, fn($q) => $q->where('storage_id', $storage))
                ->when($startDate, fn($q) => $q->where('created_at', '<=', $startDate))
                ->first();
            $head_back_purchases_details = DB::table('head_back_purchases_details')
                ->selectRaw('SUM(amount) as total_amount, SUM(bouns) as total_bouns')
                ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                ->when($storage, fn($q) => $q->where('storage_id', $storage))
                ->when($startDate, fn($q) => $q->where('created_at', '<=', $startDate))
                ->first();
            $destruction_details = DB::table('destruction_details')
                ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                ->when($storage, fn($q) => $q->where('storage_id', $storage))
                ->when($startDate, fn($q) => $q->where('created_at', '<=', $startDate))
                ->sum('amount');
            $incremental_adjustment = DB::table('product_adjustments')
                ->when($productive_id, fn($q) => $q->where('product_id', $productive_id))
                ->when($storage, fn($q) => $q->where('storage_id', $storage))
                ->when($startDate, fn($q) => $q->where('created_at', '<=', $startDate))
                ->where('type', 1)
                ->sum(DB::raw('amount'));
            $deficit_adjustment = DB::table('product_adjustments')
                ->when($productive_id, fn($q) => $q->where('product_id', $productive_id))
                ->when($storage, fn($q) => $q->where('storage_id', $storage))
                ->when($startDate, fn($q) => $q->where('created_at', '<=', $startDate))
                ->where('type', 2)
                ->sum(DB::raw('amount'));

            return ($purchases_details->total_amount + $purchases_details->total_bouns) + ($head_back_sales_details->total_amount + $head_back_sales_details->total_bouns) - (($sales_details->total_amount + $sales_details->total_bouns) + ($head_back_purchases_details->total_amount + $head_back_purchases_details->total_bouns) + $destruction_details) + $incremental_adjustment - $deficit_adjustment;
        }
        return 0;
    }
}
