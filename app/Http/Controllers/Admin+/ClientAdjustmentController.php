<?php

namespace App\Http\Controllers\Admin;

use App\Enum\DeficitType;
use Illuminate\Http\Request;
use App\Models\ClientAdjustment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ClientAdjustmentController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = ClientAdjustment::query()->with(['client']);
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
                ->editColumn('type', function ($row) {
                    return DeficitType::label($row->type);
                })
                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);

        }

        return view('Admin.CRUDS.client_adjustment.index');
    }

    public function create()
    {
        return view('Admin.CRUDS.client_adjustment.parts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'value' => 'required|numeric',
            'type' => 'required|in:1,2',
            'date' => 'required|date',
        ]);

        ClientAdjustment::create($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function edit($id)
    {

        $row = ClientAdjustment::with('client')->findOrFail($id);

        return view('Admin.CRUDS.client_adjustment.parts.edit', compact('row'));

    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'value' => 'required|numeric',
            'type' => 'required|in:1,2',
            'date' => 'required|date',
        ]);

        $row = ClientAdjustment::findOrFail($id);
        $row->update($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function destroy($id)
    {

        $row = ClientAdjustment::findOrFail($id);

        $row->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    } //end fun

    public function getClients(Request $request)
    {
        if ($request->ajax()) {

            $term = trim($request->term);
            $clients = DB::table('clients')->select('id', 'name as text')
                ->where('name', 'LIKE', '%' . $term . '%')
                ->orderBy('name', 'asc')->simplePaginate(5);

            $morePages = true;
            $pagination_obj = json_encode($clients);
            if (empty($clients->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $clients->items(),
                "pagination" => array(
                    "more" => $morePages,
                ),
            );

            return \Response::json($results);

        }

    }

}
