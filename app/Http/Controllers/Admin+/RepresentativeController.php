<?php

namespace App\Http\Controllers\Admin;

use App\Enum\RepresentativeType;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Representative;
use App\Models\Storage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class RepresentativeController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:عرض المناديب,admin')->only('index');
    //     $this->middleware('permission:تعديل المناديب,admin')->only(['edit', 'update']);
    //     $this->middleware('permission:إنشاء المناديب,admin')->only(['create', 'store']);
    //     $this->middleware('permission:حذف المناديب,admin')->only('destroy');
    // }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $representatives = Representative::query()->with(['branch', 'storage']);
            return DataTables::of($representatives)
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

                           <button ' . $edit . '   class="details btn rounded-pill btn-primary waves-effect waves-light"
                                    data-id="' . $row->id . '"
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="fa fa-eye"></i>
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
                ->editColumn('created_at', function ($representative) {
                    return date('Y/m/d', strtotime($representative->created_at));
                })
                ->escapeColumns([])
                ->make(true);
        } else {
        }
        return view('Admin.CRUDS.representative.index');
    }

    /**
     * [Description for create]
     * @return View
     */
    public function create()
    {
        $branches = Branch::all();
        $storages = Storage::all();

        return view('Admin.CRUDS.representative.parts.create', compact('branches', 'storages'));
    }

    /**
     * [Description for store]
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|min:8|max:255',
            'user_name' => 'required|min:3|max:255',
            'address' => 'required|min:3|max:255',
            'phone' => 'required|unique:representatives,phone',
            'password' => 'required',
            'branch_id' => 'required|exists:branches,id',
            'storage_id' => 'required|exists:storages,id',
            'type' => 'required|in:1,2',
        ]);

        $data['password'] = Hash::make($request->password);

        Representative::create($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]
        );
    }

    /**
     * [Description for show]
     * @param Representative $representative
     * @return JsonResponse
     */
    public function show(Representative $representative)
    {
        $html = view('Admin.CRUDS.representative.parts.show', compact('representative'))->render();

        return response()->json([
            'code' => 200,
            'html' => $html,
        ]);
    }

    /**
     * [Description for edit]
     * @param Representative $representative
     * @return View
     */
    public function edit(Representative $representative)
    {
        $branches = Branch::all();
        $storages = Storage::all();

        return view('Admin.CRUDS.representative.parts.edit', compact('representative', 'branches', 'storages'));
    }

    /**
     * [Description for update]
     * @param Request $request
     * @param Representative $representative
     * @return JsonResponse
     */
    public function update(Request $request, Representative $representative)
    {
        $data = $request->validate([
            'full_name' => 'required|min:8|max:255',
            'user_name' => 'required|min:3|max:255',
            'address' => 'required|min:3|max:255',
            'phone' => 'required|unique:representatives,phone,' . $representative->id,
            'password' => 'sometimes',
            'branch_id' => 'required|exists:branches,id',
            'storage_id' => 'required|exists:storages,id',
            'type' => 'required|in:1,2',
        ]);
        if (!$request->password) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($request->password);
        }
        $representative->update($data);

        $html = view('Admin.CRUDS.representative.parts.header')->render();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
                'html' => $html,
                'name' => $representative->user_name,
            ]
        );
    }

    public function destroy(Representative $representative)
    {
        $representative->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]
        );
    }

    public function getRepresentatives(Request $request)
    {
        if ($request->ajax()) {
            $representatives = DB::table('representatives')->where('type', RepresentativeType::REPRESENTATIVE->value)->select('id', 'full_name as text')
                ->orderBy('full_name', 'asc')->simplePaginate(3);
            $morePages = true;
            $pagination_obj = json_encode($representatives);
            if (empty($representatives->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $representatives->items(),
                "pagination" => array(
                    "more" => $morePages,
                ),
            );

            return \Response::json($results);
        }
    }
    public function getDistributors(Request $request)
    {
        if ($request->ajax()) {
            $representatives = DB::table('representatives')->where('type', RepresentativeType::DISTRIBUTOR->value)->select('id', 'full_name as text')
                ->orderBy('full_name', 'asc')->simplePaginate(3);
            $morePages = true;
            $pagination_obj = json_encode($representatives);
            if (empty($representatives->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $representatives->items(),
                "pagination" => array(
                    "more" => $morePages,
                ),
            );

            return \Response::json($results);
        }
    }

    /**
     * [Description for edit]
     * @param Representative $representative
     * @return View
     */
    public function details($id)
    {
        $representative = Representative::with('clients')->findOrFail($id);
        return view('Admin.CRUDS.representative.parts.details', compact('representative'));
    }
}
