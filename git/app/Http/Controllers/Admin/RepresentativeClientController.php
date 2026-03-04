<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Representative;
use App\Models\RepresentativeClient;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RepresentativeClientController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = Client::query()->with(['city', 'governorate']);
            return DataTables::of($rows)
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="client_id"  value="' . $row->id . '" />';
                })
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

        return view('Admin.CRUDS.representative_clients.index');
    }

    public function AddClientsToRepresentative(Request $request)
    {
        $validatedData = $request->validate([
            'client_ids' => 'required|array|min:1',
            'client_ids.*' => 'required|exists:clients,id',
            'representative_id' => 'required|integer|exists:representatives,id',
        ]);

        $representative = Representative::find($validatedData['representative_id']);
        $representative->clients()->syncWithoutDetaching($validatedData['client_ids']);

        return response()->json([
            'message' => 'تم اضافة العملاء للمندوب بنجاح.',
        ]);

    }

    public function destroy(Request $request)
    {
        RepresentativeClient::where('client_id', $request->client_id)->where('representative_id', $request->representative_id)->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);

    }
}
