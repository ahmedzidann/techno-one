<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Upload_Files;
use App\Models\Admin;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    use Upload_Files;

    public function __construct()
    {
        $this->middleware('permission:عرض المستخدمين,admin')->only('index');
        $this->middleware('permission:تعديل المستخدمين,admin')->only(['edit', 'update', 'activate']);
        $this->middleware('permission:إنشاء المستخدمين,admin')->only(['create', 'store']);
        $this->middleware('permission:حذف المستخدمين,admin')->only('destroy');
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $admins = Admin::query()->with(['employee', 'roles']);
            return Datatables::of($admins)
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
                ->editColumn('name', function ($admin) {
                    return $admin->employee?->name;
                })

                ->editColumn('image', function ($admin) {
                    return ' <img height="60px" src="' . get_file($admin->image) . '" class=" w-60 rounded"
                             onclick="window.open(this.src)">';
                })

                ->editColumn('is_active', function ($row) {
                    $active = '';
                    $operation = '';
//                    if (!checkPermission(39))
//                        $operation='disabled';
                    if ($row->is_active == 1) {
                        $active = 'checked';
                    }

                    return '<div class="form-check form-switch">
  <input ' . $operation . '  class="form-check-input activeBtn" data-id="' . $row->id . ' " type="checkbox" role="switch" id="flexSwitchCheckChecked" ' . $active . '  >
</div>';
                })

                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);

        } else {

        }
        return view('Admin.CRUDS.admin.index');
    }

    public function create()
    {
        $roles = Role::get();
        $employees = Employee::get();

        return view('Admin.CRUDS.admin.parts.create', compact('roles', 'employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
           'employee_id' => [
        'required',
        'exists:employees,id',
        function ($attribute, $value, $fail) {
            if (\DB::table('admins')->where('employee_id', $value)->exists()) {
                $fail("تمت اضافه الموظف كمستخدم من قبل");
            }
        },
    ],
            'email' => 'required|email|unique:admins,email',
            'password' => 'required',
//             'business_name'=>'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif,svg,webp,avif',
            'is_active' => 'required',
            'role_id' => 'required|exists:roles,id',

        ]);
        $data["image"] = $this->uploadFiles('admins', $request->file('image'), null);

        $data['password'] = bcrypt($request->password);

//        $data['image'] = $this->createImageFromTextManual('admins' , $request->name ,256 , '#000', '#fff');

        $admin = Admin::create(Arr::except($data, 'role_id'));

        DB::table('model_has_roles')->where('model_id', $admin->id)->delete();

        $admin->assignRole($request->input('role_id'));

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function show(Admin $admin)
    {

        $html = view('Admin.CRUDS.admin.parts.show', compact('admin'))->render();
        return response()->json([
            'code' => 200,
            'html' => $html,
        ]);

        //
    }

    public function edit(Admin $admin)
    {

        $roles = Role::get();
        $employees = Employee::get();

        $adminRoles = DB::table("model_has_roles")->where("model_has_roles.model_id", $admin->id)
            ->first();

        $admin->load('employee');

        return view('Admin.CRUDS.admin.parts.edit', compact('admin', 'roles', 'adminRoles', 'employees'));

    }

    public function update(Request $request, Admin $admin)
    {
        $data = $request->validate([
          //  'employee_id' => 'required|exists:employees,id',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'nullable',
//            'business_name'=>'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif,svg,webp,avif',
            'is_active' => 'nullable',
            'role_id' => 'required|exists:roles,id',
        ]);
        if ($request->password) {

            $data['password'] = bcrypt($request->password);

        } else {

            $data['password'] = $admin->password;
        }
        if ($request->image) {
            $data["image"] = $this->uploadFiles('admins', $request->file('image'), null);

        }
        $old = $admin;
        $admin->update(Arr::except($data, 'role_id'));

        DB::table('model_has_roles')->where('model_id', $admin->id)->delete();

        $admin->assignRole($request->input('role_id'));

        $html = view('Admin.CRUDS.admin.parts.header')->render();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
                'html' => $html,
                'name' => $admin->name,
                'logo' => get_file($admin->image),
                'business_name' => $admin->business_name,
            ]);
    }

    public function destroy(Admin $admin)
    {
        $old = $admin;

        $admin->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    } //end fun

    public function activate(Request $request)
    {

        $admin = Admin::findOrFail($request->id);
        $old = $admin;
        if ($admin->is_active == '1') {

            $admin->is_active = '0';
            $admin->save();
        } else {

            $admin->is_active = '1';
            $admin->save();
        }

        return response()->json(['status' => true]);
    }

}
