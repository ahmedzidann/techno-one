<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SupplierVoucherController extends Controller
{
    //
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = SupplierVoucher::query()->with(['supplier']);
            return DataTables::of( $rows)
                ->addColumn('action', function ($row) {

                    $edit='';
                    $delete='';


                    return '

                            <button '.$delete.'  class="btn rounded-pill btn-danger waves-effect waves-light delete"
                                    data-id="' . $row->id . '">
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="fa fa-trash"></i>
                                </span>
                            </span>
                            </button>
                       ';



                })

                ->editColumn('voucher_date', function ($row) {
                    return date('Y/m/d', $row->voucher_date);
                })



                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);


        }

        return view('Admin.CRUDS.supplierVouchers.index');
    }


    public function create()
    {

        return view('Admin.CRUDS.supplierVouchers.parts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id' ,
            'paid'=>'required|regex:/^\d+(\.\d{1,2})?$/',
            'voucher_date'=>'required|date',
        ]);

        $supplier=Supplier::findOrFail($request->supplier_id);
        if ($supplier->previous_indebtedness <$request->paid)
            return response()->json(
                [
                    'code' => 421,
                    'message' => 'قيمة الايصال اكبر من المديونية'
                ]);

        $data['publisher']=auth('admin')->user()->id;
        $data['year']=date('Y');
        $data['month']=date('m');
        $data['date']=date('Y-m-d');
        $data['voucher_date']=strtotime($request->voucher_date);

        SupplierVoucher::create($data);

        $dept=$supplier->previous_indebtedness;

//        $supplier->update([
//            'previous_indebtedness'=>$dept-$request->paid,
//        ]);


        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!'
            ]);
    }



    public function destroy( $id)
    {

        $row=SupplierVoucher::find($id);
        $paid=$row->paid;
        $supplier=Supplier::findOrFail($row->supplier_id);

        $row->delete();
//        $previous_indebtedness=$supplier->previous_indebtedness+$paid;
//        $supplier->update([
//            'previous_indebtedness'=>$previous_indebtedness,
//        ]);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!'
            ]);
    }//


    public function getSupplierForVouchers(){
        $suppliers=Supplier::get();
        return view('Admin.CRUDS.supplierVouchers.parts.suppliers',compact('suppliers'));


    }

    public function getSupplierNameForVouchers($id){
        $supplier=Supplier::findOrFail($id);
        return response()->json(['status'=>true,'name'=>$supplier->name,'id'=>$supplier->id]);
    }

    public function getSupplier(Request $request){
        if ($request->ajax()) {

            $term = trim($request->term);
            $posts = DB::table('suppliers')->select('id','name as text')
                ->where('name', 'LIKE',  '%' . $term. '%')
                ->orderBy('name', 'asc')->simplePaginate(5);

            $morePages=true;
            $pagination_obj= json_encode($posts);
            if (empty($posts->nextPageUrl())){
                $morePages=false;
            }
            $results = array(
                "results" => $posts->items(),
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return \Response::json($results);

        }

    }

}
