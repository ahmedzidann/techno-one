<?php

namespace App\Http\Controllers\Admin\Reports\Bills;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SalesBillController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = Sales::query()->with(['storage', 'client']);
            if ($request->client_id) {
                $rows->where('client_id', $request->client_id);
            }
            if ($request->fromDate) {
                $rows->where('sales_date', '>=', $request->fromDate);
            }
            if ($request->toDate) {
                $rows->where('sales_date', '<=', $request->toDate);

            }
            return DataTables::of($rows)
                ->addColumn('remain', function ($row) {
                    return $row->total - $row->paid;
                })
                ->addColumn('details', function ($row) {
                    return "<button data-id='$row->id' class='btn btn-outline-dark showDetails'>عرض تفاصيل الطلب</button>";
                })
                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);


        }

        return view('Admin.reports.bills.salesBills.index', compact('request'));
    }

}
