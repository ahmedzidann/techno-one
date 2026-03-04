<?php

namespace App\Http\Controllers\Admin;

use App\Enum\ChequeStatus;
use App\Enum\EsaleType;
use App\Http\Controllers\Controller;
use App\Models\Esalat;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ChequeController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = Esalat::query()
                ->where('type', EsaleType::CHEQUE->value)
                ->with(['client', 'bank'])
                ->when($request->status, function ($q) use ($request) {
                    $q->where('cheque_status', (int) $request->status);
                });

            return DataTables::of($rows)
                ->editColumn('created_at', function ($row) {
                    return date('Y/m/d', strtotime($row->created_at));
                })
                ->editColumn('cheque_status', function ($row) {

                    if ($row->cheque_status == ChequeStatus::IN_PROGRESS->value) {
                        return '<select name="cheque_status" class="form-control" onChange="change_cheque_status(this, ' . $row->id . ')">
                                <option value="1" ' . ($row->cheque_status == 1 ? 'selected' : '') . '>جاري التنفيذ</option>
                                <option value="2" ' . ($row->cheque_status == 2 ? 'selected' : '') . '>تم التنفيذ</option>
                                <option value="3" ' . ($row->cheque_status == 3 ? 'selected' : '') . '>مرفوض</option>
                                </select>';
                    } elseif ($row->cheque_status == ChequeStatus::ACCEPTED->value) {
                        return '<h3><span class="btn btn-success">تم التنفيذ</span></h3>';
                    } elseif ($row->cheque_status == ChequeStatus::REFUSED->value) {
                        return '<h3><span class="btn btn-danger">مرفوض</span></h3>';
                    }

                })
                ->escapeColumns([])
                ->make(true);

        }

        return view('Admin.CRUDS.cheques.index');
    }

    public function changeStatusChequeStatus(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:esalats,id',
            'status' => 'required|in:2,3',

        ]);

        Esalat::where('id', $request->id)->update([
            'cheque_status' => $request->status,
        ]);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);

    }
}
