<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\ClientSubscription;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ClientSubscriptionController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:عرض الفروع,admin')->only('index');
    //     $this->middleware('permission:تعديل الفروع,admin')->only(['edit', 'update']);
    //     $this->middleware('permission:إنشاء الفروع,admin')->only(['create', 'store']);
    //     $this->middleware('permission:حذف الفروع,admin')->only('destroy');
    // }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = ClientSubscription::query();
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

        return view('Admin.CRUDS.client_subscriptions.index');
    }

    public function create()
    {

        return view('Admin.CRUDS.client_subscriptions.parts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|unique:client_subscriptions,title',
            'discount' => 'required|numeric|max:100',

        ]);

        $data['publisher'] = auth('admin')->user()->id;

        ClientSubscription::create($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]
        );
    }

    public function edit($id)
    {

        $row = ClientSubscription::find($id);
        return view('Admin.CRUDS.client_subscriptions.parts.edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|unique:client_subscriptions,title,' . $id,
            'discount' => 'required|numeric|max:100',
        ]);

        $row = ClientSubscription::find($id);
        $row->update($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]
        );
    }

    public function destroy($id)
    {

        $row = ClientSubscription::find($id);

        $row->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]
        );
    } //end fun

}
