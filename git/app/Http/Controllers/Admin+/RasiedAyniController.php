<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Productive;
use App\Models\RasiedAyni;
use App\Models\Storage;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RasiedAyniController extends Controller
{
    //
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = Productive::query()->with(['unit', 'category']);

            if ($request->category_id) {
                $rows->where('category_id', $request->category_id);
            }
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

                ->editColumn('product_type', function ($row) {
                    if ($row->product_type == 'tam') {
                        return 'تام';
                    } elseif ($row->product_type == 'kham') {
                        return 'خام';
                    } else {
                        return '';
                    }

                })

                ->addColumn('amount', function ($row) {
                    $amount = RasiedAyni::where('productive_id', $row->id)->sum('amount');
                    return "<button data-id='$row->id' class='btn btn-outline-dark showAmount'>$amount<i class='fa fa-plus mx-2'></i></button>";
                })

                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);

        } else {
            $categories = Category::get();
        }

        return view('Admin.rasiedAyni.index', compact('categories', 'request'));
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'storage_id' => 'required|exists:storages,id',
            'productive_id' => 'required|exists:productive,id',
            // 'type'=>'required|in:wholesale,department',
            'amount' => 'required|integer',

        ]);

        $productive = Productive::findOrFail($request->productive_id);

        if ($request->type == 'wholesale') {
            $data['amount'] = $productive->num_pieces_in_package;
        }

        $data['publisher'] = auth('admin')->user()->id;
        $data['type'] = 'wholesale';

        RasiedAyni::create($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح',
                'id' => $productive->id,

            ]);

    }

    public function rasied_ayni_for_productive($id)
    {
        $productive = Productive::findOrFail($id);
        $credit = $productive->credit;
        $branches = Branch::get();

        return view('Admin.rasiedAyni.parts.credit', compact('productive', 'credit', 'branches'));

    }

    public function getStorageForBranch($id)
    {
        $branch = Branch::findOrFail($id);
        $storages = Storage::where('branch_id', $id)->get();
        return view('Admin.rasiedAyni.parts.storages', compact('storages'));

    }

    public function gitCreditForProductive($id)
    {
        $productive = Productive::findOrFail($id);
        return view('Admin.rasiedAyni.parts.table', compact('productive'));

    }

    public function destroy($id)
    {

        $row = RasiedAyni::find($id);

        $row->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    } //end fun
}
