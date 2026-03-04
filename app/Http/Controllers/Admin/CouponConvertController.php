<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CouponsConvert;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CouponConvertController extends Controller
{
    public function index(Request $request)
    {
      
        if ($request->ajax()) {

            $rows = CouponsConvert::query()
                ->with(['fromUser', 'toUser']);

            return DataTables::of($rows)

                ->addColumn('from_user', function ($row) {
                    // ثابت: اسم الشركة لو 0
                    if ($row->from_user_id == 0) {
                        return 'الشركه';
                    }
                    return $row->fromUser?->name ?? '-';
                })

                ->addColumn('to_user', function ($row) {
                    // ثابت: اسم الشركة لو 0
                    if ($row->to_user_id == 0) {
                        return 'اسم الشركة';
                    }
                    return $row->toUser?->name ?? '-';
                })

                ->editColumn('amount', fn($row) => number_format($row->amount, 2))

                ->editColumn('created_at', fn($row) => $row->created_at->format('Y/m/d'))

                ->addColumn('action', function ($row) {
                    return '
                        <button class="editBtn btn rounded-pill btn-primary"
                                data-id="' . $row->id . '">
                            <i class="fa fa-edit"></i>
                        </button>

                        <button class="btn rounded-pill btn-danger delete"
                                data-id="' . $row->id . '">
                            <i class="fa fa-trash"></i>
                        </button>
                    ';
                })

                ->escapeColumns([])
                ->make(true);
        }

        return view('Admin.CRUDS.coupons_converts.index');
    }

    public function create()
    {
        return view('Admin.CRUDS.coupons_converts.parts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|integer',
            'amount'       => 'required|numeric|min:1',
            'notes'        => 'nullable|string',
            'invoice_number'=> 'required|numeric|min:1',
            'type'=> 'required'
        ]);
        if( $data['type']==1)
            {
            $data_insert['from_user_id']=0 ;
            $data_insert['to_user_id'] =  $data['client_id'];
            }else{
            $data_insert['from_user_id']=$data['client_id'] ;
            $data_insert['to_user_id'] = 0;

            }
             $data_insert['amount'] = $data['amount'] ;
             $data_insert['notes'] = $data['notes'] ;
             $data_insert['invoice_number'] = $data['invoice_number'] ;
             $data_insert['publisher'] = auth('admin')->id();
              $data_insert['type_insert']="web";
              $data_insert['converted_at'] =now() ;
       


        CouponsConvert::create($data_insert);

        return response()->json([
            'code' => 200,
            'message' => 'تم حفظ التحويل بنجاح!',
        ]);
    }

    public function edit($id)
    {
        $row = CouponsConvert::with(['fromUser', 'toUser'])->findOrFail($id);

        return view('Admin.CRUDS.coupons_converts.parts.edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
         $data = $request->validate([
            'client_id' => 'required|integer',
             'amount'       => 'required|numeric|min:1',
             'notes'        => 'nullable|string',
             'invoice_number'=> 'required|numeric|min:1',
        ]);
         $row = CouponsConvert::findOrFail($id);
        if( $row->from_user_id==0)
            {
            $data_insert['from_user_id']=0 ;
            $data_insert['to_user_id'] =  $data['client_id'];
            }else{
            $data_insert['from_user_id']=$data['client_id'] ;
            $data_insert['to_user_id'] = 0;

            }
             $data_insert['amount'] = $data['amount'] ;
             $data_insert['notes'] = $data['notes'] ;
             $data_insert['invoice_number'] = $data['invoice_number'] ;
           
       
        $row->update($data_insert);

        return response()->json([
            'code' => 200,
            'message' => 'تم تحديث التحويل بنجاح!',
        ]);
    }

    public function destroy($id)
    {
        $row = CouponsConvert::findOrFail($id);
        $row->delete();

        return response()->json([
            'code' => 200,
            'message' => 'تم حذف السجل بنجاح!',
        ]);
    }

    public function gettraders(Request $request)
{
    if ($request->ajax()) {

        $term = trim($request->term);

        $clients = DB::table('clients')
            ->select('id', 'name as text')
            ->where(function ($q) use ($term) {
                $q->where('name', 'LIKE', "%$term%")
                  ->orWhere('code', 'LIKE', "%$term%")
                  ->orWhere('phone', 'LIKE', "%$term%");
            })
            ->orderBy('name', 'asc')
            ->simplePaginate(5);

        return response()->json([
            "results" => $clients->items(),
            "pagination" => [
                "more" => !empty($clients->nextPageUrl())
            ],
        ]);
    }
}


    public function CouponStatus($status,Request $request)
    {
        if ($request->ajax()) {

            $rows = CouponsConvert::query()
                ->with(['fromUser', 'toUser'])->where('status',$status);

            return DataTables::of($rows)

                ->addColumn('from_user', function ($row) {
                    // ثابت: اسم الشركة لو 0
                    if ($row->from_user_id == 0) {
                        return 'الشركه';
                    }
                    return $row->fromUser?->name ?? '-';
                })

                ->addColumn('to_user', function ($row) {
                    // ثابت: اسم الشركة لو 0
                    if ($row->to_user_id == 0) {
                        return 'اسم الشركة';
                    }
                    return $row->toUser?->name ?? '-';
                })

                ->editColumn('amount', fn($row) => number_format($row->amount, 2))

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

                return '
                          <button   type="button"
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
                })

                ->escapeColumns([])
                ->make(true);
        }

        return view('Admin.CRUDS.coupons_converts.coupons_status');
    }
   public function updateStatus(Request $request)
{
    $request->validate([
        'row_id' => 'required|exists:coupons_converts,id',
        'status' => 'required|string',
    ]);

    $CouponsConvert = CouponsConvert::find($request->row_id);
    $CouponsConvert->status = $request->status;
     $CouponsConvert->reason = $request->reason;
     $CouponsConvert->user_update_status =auth()->id();
    $CouponsConvert->time_update_status = now();
    $CouponsConvert->save();

    // لو عايز تحفظ السبب في جدول آخر ممكن هنا تضيفه
    // ClientStatusChangeLog::create([...]);

    return response()->json([
        'success' => true,
        'message' => 'تم تحديث الحالة بنجاح'
    ]);
}

}
