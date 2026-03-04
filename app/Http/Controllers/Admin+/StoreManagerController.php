<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Productive;
use App\Models\Sales;
use App\Models\SalesDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StoreManagerController extends Controller
{
            public function __construct()
    {
        $this->middleware('permission:عرض المسئولون عن المخازن,admin')->only('index');
        $this->middleware('permission:تعديل المسئولون عن المخازن,admin')->only(['edit', 'update']);
    }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = Sales::query()->where('status', 'in_progress')->with(['storage', 'client']);
            return DataTables::of($rows)
                ->addColumn('action', function ($row) {

                    $edit = '';
                    $delete = '';

                    return '

                           <button ' . $edit . '   class="editBtn-p btn rounded-pill btn-primary waves-effect waves-light"
                                    data-id="' . $row->id . '"
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </span>
                            </button>
                       ';

                })

                ->editColumn('status', function ($row) {
                    $statuses = [
                        'new' => ['text' => 'جديد', 'class' => 'btn-primary'],
                        'in_progress' => ['text' => 'جاري التجهيز', 'class' => 'btn-info'],
                        'complete' => ['text' => 'مكتمل', 'class' => 'btn-success'],
                        'canceled' => ['text' => 'ملغي', 'class' => 'btn-danger'],
                    ];
                    $statusesWithoutNew = [
                        'complete' => ['text' => 'مكتمل', 'class' => 'btn-success'],
                    ];

                    $currentStatus = $statuses[$row->status];

                    $dropdownHtml = '
                        <div class="dropdown">
                            <button class="btn ' . $currentStatus['class'] . ' dropdown-toggle" type="button" id="statusDropdown' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                ' . $currentStatus['text'] . '
                            </button>
                            <div class="dropdown-menu" aria-labelledby="statusDropdown' . $row->id . '">';

                    foreach ($statusesWithoutNew as $status => $info) {
                        $dropdownHtml .= '
                            <a class="dropdown-item" href="#" data-status="' . $status . '" data-row-id="' . $row->id . '">
                                ' . $info['text'] . '
                            </a>';
                    }

                    $dropdownHtml .= '
                            </div>
                        </div>';

                    return $dropdownHtml;
                })
                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])

                ->make(true);

        }

        return view('Admin.CRUDS.store_manager.index');
    }

    public function edit($id)
    {

        $row = Sales::find($id);

        return view('Admin.CRUDS.store_manager.edit', compact('row'));

    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'storage_id' => 'required|exists:storages,id',
            'total_discount' => 'nullable|numeric|min:0|max:99',
            'sales_date' => 'required|date',
            'pay_method' => 'required|in:debit,cash',
            'client_id' => 'required|exists:clients,id',
            'fatora_number' => 'required|unique:sales,fatora_number,' . $id,
            'representative_id' => 'required|exists:representatives,id',

        ]);

        $datails = $request->validate([
            'company_id' => 'required|array',
            'company_id.*' => 'required|exists:companies,id',
            'productive_id' => 'required|array',
            'productive_id.*' => 'required',
            'amount' => 'required|array',
            'amount.*' => 'required',
            'productive_sale_price' => 'required|array',
            'productive_sale_price.*' => 'required',
            'bouns' => 'required|array',
            'discount_percentage' => 'required|array',
            'batch_number' => 'required|array',
            'bouns.*' => 'required',
            'discount_percentage.*' => 'nullable|numeric|min:0|max:99',
            'batch_number.*' => 'required',
            'notes.*' => 'nullable',
        ]);

        if (count($request->amount) != count($request->productive_id)) {
            return response()->json(
                [
                    'code' => 421,
                    'message' => 'المنتج مطلوب',
                ]);
        }

        $sales = Sales::findOrFail($id);
        $sales->update($data+['status' => 'complete']);

        SalesDetails::where('sales_id', $id)->delete();

        $sql = [];

        if ($request->productive_id) {
            for ($i = 0; $i < count($request->productive_id); $i++) {

                $details = [];
                $productive = Productive::findOrFail($request->productive_id[$i]);

                $details = [

                    'sales_id' => $sales->id,
                    'company_id' => $request->company_id[$i],
                    'productive_id' => $request->productive_id[$i],
                    'productive_code' => $productive->code,
                    'amount' => $request->amount[$i],
                    'bouns' => $request->bouns[$i],
                    'discount_percentage' => $request->discount_percentage[$i],
                    'batch_number' => $request->batch_number[$i],
                    'productive_sale_price' => $request->productive_sale_price[$i],
                    'total' => ($request->productive_sale_price[$i] * $request->amount[$i]) - (($request->productive_sale_price[$i] * $request->amount[$i]) * $request->discount_percentage[$i] / 100),
                    'all_pieces' => $request->amount[$i] * $productive->num_pieces_in_package,
                    'date' => $sales->date,
                    'year' => $sales->year,
                    'month' => $sales->month,
                    'publisher' => $sales->publisher,
                    'notes' => $request->notes[$i],
                    'created_at' => $sales->created_at,
                    'updated_at' => date('Y-m-d H:i:s'),

                ];

                array_push($sql, $details);
            }
            DB::table('sales_details')->insert($sql);

            $total = SalesDetails::where('sales_id', $sales->id)->sum('total');
            $totalAfterDiscount = $total - ($total / 100 * $data['total_discount'] ?? 0);

            $sales->update([
                'total' => $total,
                'total_discount' => $data['total_discount'],
                'total_after_discount' => $totalAfterDiscount,
            ]);

        }

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

}
