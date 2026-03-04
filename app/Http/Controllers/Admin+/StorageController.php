<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Storage;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StorageController extends Controller
{
            public function __construct()
    {
        $this->middleware('permission:عرض المخازن,admin')->only('index');
        $this->middleware('permission:تعديل المخازن,admin')->only(['edit', 'update']);
        $this->middleware('permission:إنشاء المخازن,admin')->only(['create', 'store']);
        $this->middleware('permission:حذف المخازن,admin')->only('destroy');
    }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = Storage::query() ->with(['branch']);
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

        return view('Admin.CRUDS.storages.index');
    }


    public function create()
    {
        $branches=Branch::get();
        return view('Admin.CRUDS.storages.parts.create',compact('branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|unique:storages,title' ,
            'branch_id'=>'required|exists:branches,id',

        ]);

        $data['publisher']=auth('admin')->user()->id;

        Storage::create($data);



        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!'
            ]);
    }


    public function edit(  $id)
    {



        $row=Storage::find($id);
        $branches=Branch::get();

        return view('Admin.CRUDS.storages.parts.edit', compact('row','branches'));

    }

    public function update(Request $request, $id )
    {
        $data = $request->validate([
            'title' => 'required|unique:storages,title,'.$id ,
            'branch_id'=>'required|exists:branches,id',

        ]);

        $row=Storage::find($id);
        $row->update($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }


    public function destroy( $id)
    {

        $row=Storage::find($id);

        $row->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!'
            ]);
    }//end fun

}
