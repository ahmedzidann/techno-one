<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Productive;
use App\Models\PurchasesDetails;
use App\Models\Sales;
use App\Models\SalesDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PreparingItemController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:عرض الاصناف,admin')->only('index');
    //     $this->middleware('permission:تعديل الاصناف,admin')->only(['edit', 'update', 'updateIsPrepared']);

    // }
    public function index(Request $request)
    {
        $user = auth('admin')->user();

        if ($request->ajax()) {
            $rows = Sales::query()->where('status', 'in_progress')->whereHas('details', function ($q) use ($user) {
                $q->when($user->employee, fn($q) => $q->where('company_id', $user->employee?->company_id));
            })->with(['storage', 'client']);
            return DataTables::of($rows)
                ->addColumn('action', function ($row) {

                    $edit = '';
                    $delete = '';

                    return '
                           <button ' . $edit . '   class=" btn rounded-pill btn-primary waves-effect waves-light showDetails"
                                    data-id="' . $row->id . '"
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    تحضير الصنف
                                </span>
                            </span>
                            </button>';
                })
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d H:i');
                })
                ->escapeColumns([])

                ->make(true);
        }

        return view('Admin.CRUDS.prepare_items.index');
    }
    public function edit($id)
    {
        $user = auth('admin')->user();
        $row = Sales::with(['details' => fn($q) => $q->when($user->employee, fn($query) => $query->where('company_id', $user->employee->company_id))])->find($id);

        $view = view('Admin.CRUDS.prepare_items.parts.editForm', compact('row'))->render();

        return response()->json(['view' => $view, 'row' => $row]);
    }

    public function update(Request $request, $id)
    {

        $datails = $request->validate([
            'amount' => 'required|array',
            'amount.*' => 'required',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|max:500',
            'sales_details_id.*' => 'exists:sales_details,id',

        ]);

        if ($request->sales_details_id) {
            foreach ($request->sales_details_id as $key => $value) {
                SalesDetails::where('id', $value)->update([
                    'amount' => $request->amount[$key],
                    'notes' => $request->notes[$key],
                ]);
            }
        }

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function updateIsPrepared(Request $request)
    {
        $row = SalesDetails::findOrFail($request->id);
      
        if ($request->is_prepared == 1) {
            $prices = $this->getPrice($row->productive_id, $request->batch_number);
            $row->amount = $request->amount;
            $row->notes = $request->notes;
            $row->batch_number = $request->batch_number;
            $row->productive_sale_price = $prices['sell_price'];
            $row->productive_buy_price = $prices['buy_price'];
            $row->total = $prices['sell_price'] * $request->amount;
        }
        $row->is_prepared = $request->is_prepared;
        $row->save();

        return response()->json(['success' => true]);
    }

    public function getBatchNumbers(Request $request)
    {
        if ($request->ajax()) {
            $batchs = DB::table('purchases_details')
                ->select(['batch_number as text', 'batch_number as id'])
                ->where('batch_number', '!=', null)
                ->distinct()
                ->simplePaginate(3);

            $morePages = true;
            $pagination_obj = json_encode($batchs);
            if (empty($batchs->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $batchs->items(),
                "pagination" => array(
                    "more" => $morePages,
                ),
            );

            return \Response::json($results);

        }
    }

    public function getPrice($id, $batchNumber)
    {

        $productFromPurchase = PurchasesDetails::where('productive_id', $id)
            ->when($batchNumber, fn($q) => $q->where('batch_number', $batchNumber))
            ->latest()
            ->first();
        $productFromSales = SalesDetails::where('productive_id', $id)
            ->when($batchNumber, fn($q) => $q->where('batch_number', $batchNumber))
            ->latest()
            ->first();

        $buyPrice = 0;
        if ($productFromPurchase) {
            $buyPrice = $productFromPurchase->total / $productFromPurchase->amount;
        } else {
            $buyPrice = Productive::where('id', $id)->first()->one_buy_price;
        }

        $salePrice = 0;
        if ($productFromSales) {
            $salePrice = $productFromSales->total / $productFromSales->amount;
        } else {
            $salePrice = Productive::where('id', $id)->first()->one_sell_price;
        }

        return [
            'buy_price' => $buyPrice,
            'sell_price' => $salePrice,
        ];
    }

}
