<?php

namespace App\Http\Controllers\Admin\Reports\Storage;

use App\Http\Controllers\Controller;
use App\Models\Productive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StorageCheckController extends Controller
{
    protected $product;
    public function index(Request $request)
    {
        $storage = $request->input('storage_id');
        $productive_id = $request->input('product_id');

        if ($request->ajax()) {
            $baseQuery = DB::table('rasied_ayni')
                ->select(
                    'productive_id',
                    DB::raw('SUM(amount) as total_amount'),
                    DB::raw("'رصيد عيني' as type"),
                    DB::raw('1 as process'),
                    DB::raw('0 as bouns'),
                )
                ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                ->when($storage, fn($q) => $q->where('storage_id', $storage))
                ->groupBy('productive_id');

            $query = $baseQuery
                ->unionAll(
                    DB::table('sales_details')
                        ->select(
                            'productive_id',
                            DB::raw('SUM(amount) as total_amount'),
                            DB::raw("'مبيعات' as type"),
                            DB::raw('2 as process'),
                            DB::raw('SUM(bouns) as bouns'),
                        )
                        ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                        ->when($storage, fn($q) => $q->where('storage_id', $storage))
                        ->where('is_prepared', 1)
                        ->groupBy('productive_id')
                )
                ->unionAll(
                    DB::table('purchases_details')
                        ->select(
                            'productive_id',
                            DB::raw('SUM(amount) as total_amount'),
                            DB::raw("'مشتريات' as type"),
                            DB::raw('3 as process'),
                            DB::raw('SUM(bouns) as bouns'),
                        )
                        ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                        ->when($storage, fn($q) => $q->where('storage_id', $storage))
                        ->groupBy('productive_id')
                )
                ->unionAll(
                    DB::table('head_back_sales_details')
                        ->select(
                            'productive_id',
                            DB::raw('SUM(amount) as total_amount'),
                            DB::raw("'مرتجع مبيعات' as type"),
                            DB::raw('4 as process'),
                            DB::raw('SUM(bouns) as bouns'),
                        )
                        ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                        ->when($storage, fn($q) => $q->where('storage_id', $storage))
                        ->groupBy('productive_id')
                )
                ->unionAll(
                    DB::table('head_back_purchases_details')
                        ->select(
                            'productive_id',
                            DB::raw('SUM(amount) as total_amount'),
                            DB::raw("'مرتجع مشتريات' as type"),
                            DB::raw('5 as process'),
                            DB::raw('SUM(bouns) as bouns'),
                        )
                        ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                        ->when($storage, fn($q) => $q->where('storage_id', $storage))
                        ->groupBy('productive_id')
                )
                ->unionAll(
                    DB::table('destruction_details')
                        ->select(
                            'productive_id',
                            DB::raw('SUM(amount) as total_amount'),
                            DB::raw("'اهلاك' as type"),
                            DB::raw('6 as process'),
                            DB::raw('0 as bouns'),
                        )
                        ->when($productive_id, fn($q) => $q->where('productive_id', $productive_id))
                        ->when($storage, fn($q) => $q->where('storage_id', $storage))
                        ->groupBy('productive_id')
                )
                ->unionAll(
                    DB::table('product_adjustments')
                         ->where('type', 1)
                        ->select(
                            'product_id as productive_id',
                            DB::raw('SUM(amount) as total_amount'),
                            DB::raw("'تسوية بالزيادة' as type"),
                            DB::raw('7 as process'),
                            DB::raw('0 as bouns'),
                        )
                        ->when($productive_id, fn($q) => $q->where('product_id', $productive_id))
                        ->when($storage, fn($q) => $q->where('storage_id', $storage))
                        ->groupBy('productive_id')
                )
                ->unionAll(
                    DB::table('product_adjustments')
                        ->where('type', 2)
                        ->select(
                            'product_id as productive_id',
                            DB::raw('SUM(amount) as total_amount'),
                            DB::raw("'تسوية بالعجز' as type"),
                            DB::raw('8 as process'),
                            DB::raw('0 as bouns'),
                        )
                        ->when($productive_id, fn($q) => $q->where('product_id', $productive_id))
                        ->when($storage, fn($q) => $q->where('storage_id', $storage))
                        ->groupBy('productive_id')
                );

            $groupedQuery = DB::query()
                ->fromSub($query, 'subquery')
                ->select(
                    'productive_id',
                    DB::raw('SUM(CASE WHEN type = "رصيد عيني" THEN total_amount ELSE 0 END) as rasied_ayni , SUM(CASE WHEN type = "رصيد عيني" THEN bouns ELSE 0 END) as rasied_ayni_bouns'),
                    DB::raw('SUM(CASE WHEN type = "مبيعات" THEN total_amount ELSE 0 END) as sales_details, SUM(CASE WHEN type = "مبيعات" THEN bouns ELSE 0 END) as sales_details_bouns'),
                    DB::raw('SUM(CASE WHEN type = "مشتريات" THEN total_amount ELSE 0 END) as purchases_details, SUM(CASE WHEN type = "مشتريات" THEN bouns ELSE 0 END) as purchases_details_bouns'),
                    DB::raw('SUM(CASE WHEN type = "مرتجع مبيعات" THEN total_amount ELSE 0 END) as head_back_sales_details, SUM(CASE WHEN type = "مرتجع مبيعات" THEN bouns ELSE 0 END) as head_back_sales_details_bouns'),
                    DB::raw('SUM(CASE WHEN type = "مرتجع مشتريات" THEN total_amount ELSE 0 END) as head_back_purchases_details, SUM(CASE WHEN type = "مرتجع مشتريات" THEN bouns ELSE 0 END) as head_back_purchases_details_bouns'),
                    DB::raw('SUM(CASE WHEN type = "اهلاك" THEN total_amount ELSE 0 END) as destruction_details, SUM(CASE WHEN type = "اهلاك" THEN bouns ELSE 0 END) as destruction_details_bouns'),
                    DB::raw('SUM(CASE WHEN type = "تسوية بالزيادة" THEN total_amount ELSE 0 END) as increment_adjustments, SUM(CASE WHEN type = "تسوية بالزيادة" THEN bouns ELSE 0 END) as increment_adjustments_bouns'),
                    DB::raw('SUM(CASE WHEN type = "تسوية بالعجز" THEN total_amount ELSE 0 END) as deficit_adjustments, SUM(CASE WHEN type = "تسوية بالعجز" THEN bouns ELSE 0 END) as deficit_adjustments_bouns')
                )
                ->groupBy('productive_id');

            return DataTables::of($groupedQuery)
                ->addIndexColumn()
                ->addColumn('total', function ($row) {

                    return $row->rasied_ayni +
                    ($row->purchases_details + $row->purchases_details_bouns) +
                    ($row->head_back_sales_details + $row->head_back_sales_details_bouns) +
                    $row->increment_adjustments -
                    $row->deficit_adjustments -
                    ($row->sales_details + $row->sales_details_bouns) -
                    ($row->head_back_purchases_details + $row->head_back_purchases_details_bouns) -
                    $row->destruction_details;

                })
                ->addColumn('product', function ($row) {
                    $this->product = Productive::find($row->productive_id);
                    return $this->product?->name;
                })
                ->addColumn('code', function ($row) {
                    return $this->product?->code;
                })
                ->editColumn('sales_details', function ($row) {
                    if ($row->sales_details_bouns) {
                       return $row->sales_details + $row->sales_details_bouns . "($row->sales_details_bouns)";
                    }
                    return $row->sales_details;
                })
                ->editColumn('purchases_details', function ($row) {
                    if ($row->purchases_details_bouns) {
                       return $row->purchases_details + $row->purchases_details_bouns . "($row->purchases_details_bouns)";
                    }
                    return $row->purchases_details;
                })
                ->editColumn('head_back_sales_details', function ($row) {
                    if ($row->head_back_sales_details_bouns) {
                       return $row->head_back_sales_details + $row->head_back_sales_details_bouns . "($row->head_back_sales_details_bouns)";
                    }
                    return $row->head_back_sales_details;
                })
                ->editColumn('head_back_purchases_details', function ($row) {
                    if ($row->head_back_purchases_details_bouns) {
                       return $row->head_back_purchases_details + $row->head_back_purchases_details_bouns . "($row->head_back_purchases_details_bouns)";
                    }
                    return $row->head_back_purchases_details;
                })
                ->escapeColumns([])
                ->make(true);
        }
        return view('Admin.reports.storage.index');
    }

}
