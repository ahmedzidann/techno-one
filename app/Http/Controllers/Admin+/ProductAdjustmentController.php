<?php

namespace App\Http\Controllers\Admin;

use App\Enum\DeficitType;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ProductAdjustment;
use App\Models\Productive;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductAdjustmentController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('Admin.CRUDS.product_adjustment.index');
        }

        return $this->generateDataTable(
            ProductAdjustment::query()->with(['storage', 'supervisor', 'product'])
        );
    }
    private function generateDataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return $this->generateActionButtons($row);
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

    private function generateActionButtons($row)
    {
        return '
            <button class="editBtn btn rounded-pill btn-primary waves-effect waves-light"
                    data-id="' . $row->id . '">
                <span class="svg-icon svg-icon-3">
                    <i class="fa fa-edit"></i>
                </span>
            </button>
            <button class="btn rounded-pill btn-danger waves-effect waves-light delete"
                    data-id="' . $row->id . '">
                <span class="svg-icon svg-icon-3">
                    <i class="fa fa-trash"></i>
                </span>
            </button>';
    }

    public function create()
    {
        $lastId = DB::table('product_adjustments')->latest('id')->max('report_number') ?? 0;
        return view('Admin.CRUDS.product_adjustment.create', ['count' => $lastId]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'report_number' => 'required|integer', // Replace table_name with your actual table
            'storage_id' => 'required|exists:storages,id',
            'supervisor_id' => 'required|exists:employees,id',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:productive,id',
            'amount' => 'required|array',
            'amount.*' => 'required|integer|min:0',
            'type' => 'required|array',
            'type.*' => 'required|integer|in:1,2',
            'date' => 'required|date',
        ]);

        // Prepare data for bulk insertion
        $dataToInsert = [];
        foreach ($validatedData['product_id'] as $index => $productId) {
            $dataToInsert[] = [
                'report_number' => $validatedData['report_number'],
                'storage_id' => $validatedData['storage_id'],
                'supervisor_id' => $validatedData['supervisor_id'],
                'product_id' => $productId,
                'amount' => $validatedData['amount'][$index] ?? 0,
                'type' => $validatedData['type'][$index] ?? 0,
                'date' => $validatedData['date'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        ProductAdjustment::insert($dataToInsert);

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'تم اضافة تسوية المنتجات بنجاح.',
        ], 201);

    }

    public function edit($id)
    {
        $row = ProductAdjustment::findOrFail($id);
        $employees = Employee::all();
        $storages = Storage::all();
        $products = Productive::all();
        return view('Admin.CRUDS.product_adjustment.parts.edit', compact('row', 'employees', 'storages', 'products'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'report_number' => 'required|integer', // Replace table_name with your actual table
            'storage_id' => 'required|exists:storages,id',
            'supervisor_id' => 'required|exists:employees,id',
            'product_id' => 'required|exists:productive,id',
            'amount' => 'required|integer|min:0',
            'type' => 'required|integer|in:1,2',
            'date' => 'required|date',
        ]);

        $adjustment = ProductAdjustment::findOrFail($id);
        $adjustment->update($validatedData);

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'تم تعديل تسوية المنتج بنجاح.',
        ], 201);

    }

    public function destroy($id)
    {
        $row = ProductAdjustment::find($id);
        $row->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);

    }
    public function makeRowDetailsForProductAdjustment()
    {
        $id = rand(2, 999999999999999);
        $html = view('Admin.CRUDS.product_adjustment.parts.details', compact('id'))->render();
        return response()->json(['status' => true, 'html' => $html, 'id' => $id]);

    }
}
