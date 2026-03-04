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
                ->where('name', 'LIKE', "%$term%")
                ->orderBy('name', 'asc')
                ->simplePaginate(5);

            return response()->json([
                "results" => $clients->items(),
                "pagination" => ["more" => !empty($clients->nextPageUrl())],
            ]);
        }
    }
}
