<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\Storage;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:عرض الموظفون,admin')->only('index');
        $this->middleware('permission:تعديل الموظفون,admin')->only(['edit', 'update']);
        $this->middleware('permission:إنشاء الموظفون,admin')->only(['create', 'store']);
        $this->middleware('permission:حذف الموظفون,admin')->only('destroy');
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = Employee::query()->with(['company', 'storage']);
            return DataTables::of($rows)
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

        return view('Admin.CRUDS.employees.index');
    }
    public function create()
    {
        $companies = Company::get();
        $storages = Storage::get();
        return view('Admin.CRUDS.employees.parts.create', compact('companies', 'storages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'phone_number' => 'required|unique:employees,phone_number',
            'company_id' => 'required|exists:companies,id',
            'storage_id' => 'required|exists:storages,id',
        ]);

        Employee::create($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function edit($id)
    {

        $row = Employee::find($id);
        $companies = Company::get();
        $storages = Storage::get();

        return view('Admin.CRUDS.employees.parts.edit', compact('row', 'companies', 'storages'));

    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required',
            'phone_number' => 'required|unique:employees,phone_number,' . $id,
            'company_id' => 'required|exists:companies,id',
            'storage_id' => 'required|exists:storages,id',
        ]);

        $row = Employee::find($id);
        $row->update($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function destroy($id)
    {
        $row = Employee::find($id);
        $row->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    } //end fun

        public function getEmployee(Request $request){
        if ($request->ajax()) {

            $term = trim($request->term);
            $employees = DB::table('employees')->select('id','name as text')
                ->where('name', 'LIKE',  '%' . $term. '%')
                ->orderBy('name', 'asc')->simplePaginate(5);

            $morePages=true;
            $pagination_obj= json_encode($employees);
            if (empty($employees->nextPageUrl())){
                $morePages=false;
            }
            $results = array(
                "results" => $employees->items(),
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return \Response::json($results);

        }

    }

}
