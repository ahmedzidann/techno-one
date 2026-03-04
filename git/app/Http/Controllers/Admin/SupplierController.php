<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{
            public function __construct()
    {
        $this->middleware('permission:عرض الموردين,admin')->only('index');
        $this->middleware('permission:تعديل الموردين,admin')->only(['edit', 'update']);
        $this->middleware('permission:إنشاء الموردين,admin')->only(['create', 'store']);
        $this->middleware('permission:حذف الموردين,admin')->only('destroy');
    }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = Supplier::query()->with(['city','governorate']);
            return DataTables::of( $rows)
                ->addColumn('action', function ($row) {

                    $edit='';
                    $delete='';


                    return '
                            <button '.$edit.'   class="editBtn btn rounded-pill btn-primary waves-effect waves-light"
                                    data-id="' . $row->id . '"
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="fa fa-edit"></i>
                                </span>
                            </span>
                            </button>
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



                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);


        }

        return view('Admin.CRUDS.suppliers.index');
    }


    public function create()
    {
        $governorates=Area::where('from_id',null)->get();
        return view('Admin.CRUDS.suppliers.parts.create',compact('governorates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required' ,
            'code'=>'required|unique:suppliers,code',
            'phone'=>'required|unique:suppliers,phone',
            'governorate_id'=>'required|exists:areas,id',
            'city_id'=>'required|exists:areas,id',
            'address'=>'nullable',
            'previous_indebtedness'=>'required|integer',
        ]);



        $data['publisher']=auth('admin')->user()->id;

        Supplier::create($data);



        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!'
            ]);
    }


    public function edit(  $id)
    {



        $row=Supplier::find($id);
        $governorates=Area::where('from_id',null)->get();
        $cities=Area::where('from_id',$row->governorate_id)->get();

        return view('Admin.CRUDS.suppliers.parts.edit', compact('row','governorates','cities'));

    }

    public function update(Request $request, $id )
    {
        $data = $request->validate([
            'name' => 'required' ,
            'code'=>'required|unique:suppliers,code,'.$id,
            'phone'=>'required|unique:suppliers,phone,'.$id,
            'governorate_id'=>'required|exists:areas,id',
            'city_id'=>'required|exists:areas,id',
            'address'=>'nullable',
            'previous_indebtedness'=>'required|integer',
        ]);


        $row=Supplier::find($id);
        $row->update($data);



        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }


    public function destroy( $id)
    {

        $row=Supplier::find($id);

        $row->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!'
            ]);
    }//end fun

}
