<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Productive;
use App\Models\Sales;
use App\Models\SalesDetails;
use App\Services\CustomerAccount;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use Yajra\DataTables\DataTables;

class SalesController extends Controller
{
    /**
     * STATUSES
     *
     * @var array
     */
    private const STATUSES = [
        'new' => ['text' => 'جديد', 'class' => 'btn-primary'],
        'in_progress' => ['text' => 'جاري التجهيز', 'class' => 'btn-info'],
        'complete' => ['text' => 'مكتمل', 'class' => 'btn-success'],
        'canceled' => ['text' => 'ملغي', 'class' => 'btn-danger'],
    ];

    public function __construct()
    {
        $this->middleware('permission:عرض الفواتير,admin')->only('index');
        $this->middleware('permission:تعديل الفواتير,admin')->only(['edit', 'update']);
        $this->middleware('permission:إنشاء الفواتير,admin')->only(['create', 'store']);
        $this->middleware('permission:حذف الفواتير,admin')->only('destroy');
    }

    /**
     * [Description for index]
     *
     * @param Request $request
     *
     * @return JsonResponse|View
     *
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('Admin.CRUDS.sales.index');
        }

        $query = Sales::query()->with(['storage', 'client']);

        $this->applyFilters($query, $request);

        return $this->generateDataTable($query);
    }

    /**
     * [Description for applyFilters]
     *
     * @param mixed $query
     * @param Request $request
     *
     * @return void
     *
     */
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('sales_date', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('representative_id')) {
            $query->where('representative_id', $request->representative_id);
        }
    }

    /**
     * [Description for generateDataTable]
     *
     * @param mixed $query
     *
     * @return JsonResponse
     *
     */
    private function generateDataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return $this->generateActionButtons($row);
            })
            ->addColumn('details', function ($row) {
                return "<button data-id='$row->id' class='btn btn-outline-dark showDetails'>عرض تفاصيل الطلب</button>";
            })
            ->editColumn('status', function ($row) {
                return $this->generateStatusDropdown($row);
            })
            ->editColumn('created_at', function ($admin) {
                return date('Y/m/d', strtotime($admin->created_at));
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * [Description for generateActionButtons]
     *
     * @param mixed $row
     *
     * @return string
     *
     */
    private function generateActionButtons($row)
    {
        return '
            <button class="editBtn-p btn rounded-pill btn-primary waves-effect waves-light"
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

    private function generateStatusDropdown($row)
    {
        $currentStatus = self::STATUSES[$row->status];
        $statusesWithoutNew = array_diff_key(self::STATUSES, ['new' => []]);

        $dropdownHtml = "<div class='dropdown'>
            <button class='btn {$currentStatus['class']} dropdown-toggle' type='button'
                    id='statusDropdown{$row->id}' data-toggle='dropdown'
                    aria-haspopup='true' aria-expanded='false'>
                {$currentStatus['text']}
            </button>
            <div class='dropdown-menu' aria-labelledby='statusDropdown{$row->id}'>";

        foreach ($statusesWithoutNew as $status => $info) {
            $dropdownHtml .= "<a class='dropdown-item' href='#'
                                data-status='{$status}'
                                data-row-id='{$row->id}'>
                                {$info['text']}
                             </a>";
        }

        return $dropdownHtml . '</div></div>';
    }

    public function create()
    {
        $lastId = DB::table('sales')->latest('id')->value('id') ?? 0;
        return view('Admin.CRUDS.sales.create', ['count' => $lastId]);
    }

    public function store(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                [$data, $details] = $this->validateData($request);
                $sales = $this->createSalesRecord($data);
                $this->processDetailsAndUpdateTotals($request, $sales, $data['total_discount'] ?? 0);

                return $this->successResponse('تمت العملية بنجاح!');
            });
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    private function validateData(Request $request, $id = null)
    {
        $data = $request->validate([
            'storage_id' => 'required|exists:storages,id',
            'total_discount' => 'nullable|numeric|min:0|max:99',
            'sales_date' => 'required|date',
            'pay_method' => 'required|in:debit,cash',
            'client_id' => 'required|exists:clients,id',
            // 'fatora_number' => 'required|unique:sales,fatora_number' . ($id ? ',' . $id : ''),
        ]);

        $details = $request->validate([
            'productive_id' => 'required|array',
            'productive_id.*' => 'required',
            'amount' => 'required|array',
            'amount.*' => 'required',
            'productive_sale_price' => 'required|array',
            'productive_sale_price.*' => 'required',
            'bouns' => 'required|array',
            'discount_percentage' => 'required|array',
            'likely_discount' => 'required|array',
            'likely_sale' => 'required|array',
            'batch_number' => 'required|array',
            'bouns.*' => 'required',
            'discount_percentage.*' => 'required|numeric|max:100|min:0',
            'batch_number.*' => 'required',
            'likely_discount.*' => 'required',
            'likely_sale.*' => 'required',
        ]);

        if (count($request->amount) !== count($request->productive_id)) {
            throw ValidationException::withMessages(['المنتج مطلوب']);
        }

        return [$data, $details];
    }

    private function createSalesRecord(array $data)
    {
        $now = Carbon::now();
        $latestId = DB::table('sales')->latest('id')->value('id') ?? 0;
        $client = DB::table('clients')->where('id', $data['client_id'])->first();

        return Sales::create(
            array_merge($data, [
                'publisher' => auth('admin')->user()->id,
                'sales_number' => $latestId + 1,
                'date' => $now->toDateString(),
                'month' => $now->month,
                'year' => $now->year,
                'governorate_id' => $client->governorate_id,
                'city_id' => $client->city_id,
                'region_id' => $client->region_id,
                'tele_sales_am' => $client->tele_sales_am,
                'tele_sales_pm' => $client->tele_sales_pm,
                'representative_id' => $client->representative_id,
                'distributor_id' => $client->distributor_id,
                'client_subscription_id' => $client->client_subscription_id,
                'payment_category' => $client->payment_category,
            ])
        );
    }

    private function processDetailsAndUpdateTotals(Request $request, Sales $sales, $totalDiscount)
    {
        if (empty($request->productive_id)) {
            return;
        }

        $detailsData = $this->prepareDetailsData($request, $sales);
        DB::table('sales_details')->insert($detailsData);

        $this->updateSalesTotals($sales, $totalDiscount);
    }

    private function prepareDetailsData(Request $request, Sales $sales)
    {
        $detailsData = [];
        $now = Carbon::now();

        foreach ($request->productive_id as $i => $productiveId) {
            $productive = Productive::findOrFail($productiveId);
            $salePrice = $request->productive_sale_price[$i];
            $amount = $request->amount[$i];
            $discountPercentage = $request->likely_discount[$i] - $request->discount_percentage[$i];
            $total = $this->calculateTotal($salePrice, $amount, $discountPercentage);
            $detailsData[] = [
                'storage_id' => $sales->storage_id,
                'sales_id' => $sales->id,
                'company_id' => $request->company_id[$i],
                'productive_id' => $productiveId,
                'productive_code' => $productive->code,
                'amount' => $amount,
                'bouns' => $request->bouns[$i],
                'discount_percentage' => $discountPercentage,
                'likely_discount' => $request->likely_discount[$i],
                'likely_sale' => $request->likely_sale[$i],
                'profit_value' => ($request->discount_percentage[$i] * $salePrice / 100) * $amount,
                'batch_number' => $request->batch_number[$i],
                'productive_buy_price' => $salePrice,
                'total' => $total,
                'one_sell_price' => $total / $amount,
                'all_pieces' => $amount * $productive->num_pieces_in_package,
                'date' => $sales->date,
                'year' => $sales->year,
                'month' => $sales->month,
                'publisher' => $sales->publisher,
                'created_at' => $sales->created_at ?? $now,
                'updated_at' => $now,
            ];
        }

        return $detailsData;
    }

    private function calculateTotal($salePrice, $amount, $discountPercentage)
    {
        $subtotal = $salePrice * $amount;
        return $subtotal - ($subtotal * $discountPercentage / 100);
    }

    private function updateSalesTotals(Sales $sales, $totalDiscount)
    {
        $total = SalesDetails::where('sales_id', $sales->id)->sum('total');
        $totalAfterDiscount = $total - ($total * $totalDiscount / 100);

        $sales->update([
            'total' => $total,
            'total_discount' => $totalDiscount,
            'total_after_discount' => $totalAfterDiscount,
        ]);
    }

    private function successResponse($message, $code = 200)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
        ]);
    }

    private function errorResponse($message, $code = 500)
    {
        return response()->json([
            'code' => $code,
            'message' => 'حدث خطأ أثناء معالجة الطلب',
            'error' => $message,
        ]);
    }

    public function edit($id)
    {
        $row = Sales::findOrFail($id);
        $details = SalesDetails::with('product.batches')->where('sales_id', $row->id)->get();
        return view('Admin.CRUDS.sales.edit', compact('row', 'details'));
    }

    public function update(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                [$data, $details] = $this->validateData($request, $id);

                $sales = Sales::findOrFail($id);
                $sales->update($data);

                SalesDetails::where('sales_id', $id)->delete();
                $this->processDetailsAndUpdateTotals($request, $sales, $data['total_discount'] ?? 0);

                return $this->successResponse('تمت العملية بنجاح!');
            });
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function destroy($id)
    {
        Sales::findOrFail($id)->delete();
        return $this->successResponse('تمت العملية بنجاح!');
    }

    public function getSalesDetails($id)
    {
        $purchase = Sales::findOrFail($id);
        $rows = SalesDetails::where('sales_id', $id)
            ->with(['productive', 'sales'])
            ->get();
        return view('Admin.CRUDS.sales.parts.salesDetails', compact('rows'));
    }

    public function makeRowDetailsForSalesDetails()
    {
        $id = rand(2, 999999999999999);
        $html = view('Admin.CRUDS.sales.parts.details', compact('id'))->render();
        return response()->json(['status' => true, 'html' => $html, 'id' => $id]);
    }

    public function updateStatus(Request $request)
    {
        $sales = Sales::findOrFail($request->id);
        $sales->status = $request->status;
        $sales->save();

        return response()->json(['success' => true]);
    }

    public function getSalesForClient(Request $request, $client_id)
    {
        if ($request->ajax()) {
            $numbers = DB::table('sales')->where('client_id', $client_id)->select('id', 'id as text')
                ->orderBy('id', 'asc')->simplePaginate(3);
            $morePages = true;
            $pagination_obj = json_encode($numbers);
            if (empty($numbers->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $numbers->items(),
                "pagination" => array(
                    "more" => $morePages,
                ),
            );

            return \Response::json($results);
        }
    }

    public function customerBalance(Request $request)
    {
        return response()->json([
            'message' => 'success',
            'balance' => CustomerAccount::CustomerBalance($request->client_id),
            'code' => 200
        ]);
    }
}
