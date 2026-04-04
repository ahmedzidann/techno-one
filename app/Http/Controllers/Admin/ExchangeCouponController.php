<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CouponsConvert;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ExchangeCouponController extends Controller
{
    public function index(Request $request,$status)
    {
      
         if ($request->ajax()) {

            $rows = CouponsConvert::query()
                ->with(['fromUser', 'toUser','payMethod'])->where('status',$status)->where('to_user_id',0);

            return DataTables::of($rows)

                ->addColumn('from_user', function ($row) {
                    // ثابت: اسم الشركة لو 0
                    if ($row->from_user_id == 0) {
                        return 'تكنو وان';
                    }
                    return $row->fromUser?->name ?? '-';
                })

                ->addColumn('to_user', function ($row) {
                    // ثابت: اسم الشركة لو 0
                    if ($row->to_user_id == 0) {
                        return 'تكنو وان';
                    }
                    return $row->toUser?->name ?? '-';
                })

                ->editColumn('amount', fn($row) => number_format($row->amount, 2))
                 ->addColumn('payMethod', function ($row) {
                 return $row->payMethod->title ?? '-';
                 })

                ->editColumn('created_at', fn($row) => $row->created_at->format('Y/m/d'))

                ->addColumn('action', function ($row) {
                      $edit = '';
                    $delete = '';

                      if($row->status=='pending')
                        {
                            $title="تحت الاجراء";
                            $class="btn btn-info";


                        }else if($row->status=='approved')
                        {
                            $title="مقبول";
                            $class="btn btn-success";
                        }elseif($row->status=='refused')
                        {
                             $title="مرفوض";
                            $class="btn btn-danger";
                        }

            if (auth('admin')->check() && auth('admin')->user()->can('الاجراء علي الوارد للصرف')) {

    return '
        <button type="button"
            class="' . $class . ' openModalBtn"
            data-id="' . $row->id . '"
            data-status="' . $row->status . '"
            data-reason="' . $row->reason . '"
            data-bs-toggle="modal"
            data-bs-target="#statusModal">

            <span class="svg-icon svg-icon-3">
                ' . $title . '
            </span>

        </button>';
}

                })

                ->escapeColumns([])
                ->make(true);
        }

        return view('Admin.CRUDS.coupons_converts.coupons_status');
    }

}
