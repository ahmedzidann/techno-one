<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unite;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UnitController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:عرض الوحدات,admin')->only('index');
        $this->middleware('permission:تعديل الوحدات,admin')->only(['edit', 'update']);
        $this->middleware('permission:إنشاء الوحدات,admin')->only(['create', 'store']);
        $this->middleware('permission:حذف الوحدات,admin')->only('destroy');
    }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = Unite::query();
            return Datatables::of($rows)
                ->addColumn('action', function ($row) {

                    $edit = '';
                    $delete = '';

                    return '
                            <button ' . $edit . '   class="editBtn btn rounded-pill btn-primary waves-effect waves-light"
                                    data-id="' . $row->id . '"
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="fa fa-edit"></i>
                                </span>
                            </span>
                            </button>
                            <button ' . $delete . '  class="btn rounded-pill btn-danger waves-effect waves-light delete"
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

        return view('Admin.CRUDS.unites.index');
    }

    public function create()
    {

        return view('Admin.CRUDS.unites.parts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|unique:unites,title',

        ]);

        $data['publisher'] = auth('admin')->user()->id;

        Unite::create($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function edit($id)
    {

        $row = Unite::find($id);
        return view('Admin.CRUDS.unites.parts.edit', compact('row'));

    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|unique:unites,title,' . $id,

        ]);

        $row = Unite::find($id);
        $row->update($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function destroy($id)
    {

        $row = Unite::find($id);

        $row->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    } //end fun

}
