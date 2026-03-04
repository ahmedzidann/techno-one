<?php

namespace App\Http\Controllers\Admin;

use App\Enum\PaymentCategory;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientPaymentSetting;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClientPaymentSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:عرض إعدادات تسديد العملاء,admin')->only('index');
        $this->middleware('permission:تعديل إعدادات تسديد العملاء,admin')->only(['edit', 'update']);
        $this->middleware('permission:إنشاء إعدادات تسديد العملاء,admin')->only(['create', 'store']);
        $this->middleware('permission:حذف إعدادات تسديد العملاء,admin')->only('destroy');
    }
    public function index(Request $request)
    {
        // payment_month
        // client_payment_setting_id
        if ($request->ajax()) {
            $rows = ClientPaymentSetting::query();
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
                ->addColumn('from_date', function ($row) {
                    return $row->from_day . '/' . $row->month;
                })
                ->addColumn('to_date', function ($row) {
                    return $row->to_day . '/' . $row->month;
                })
                ->editColumn('created_at', function ($row) {
                    return date('Y/m/d', strtotime($row->created_at));
                })
                ->addColumn('category', function ($row) {
                    return PaymentCategory::getCategoriesSelect()[$row->payment_category] ?? 'فئة غير معروفة';
                })
                ->escapeColumns([])
                ->make(true);

        }

        return view('Admin.CRUDS.client_payment_setting.index');
    }

    public function create()
    {
        $paymentCategories = PaymentCategory::getCategoriesSelect();

        return view('Admin.CRUDS.client_payment_setting.parts.create', compact('paymentCategories'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'payment_category' => 'required|integer|between:1,4',
            'month' => 'required|integer|between:1,12',
            'title' => 'required|string|max:255',
            'from_day' => 'required|integer|between:1,31',
            'to_day' => 'required|integer|between:1,31',
        ]);

        ClientPaymentSetting::create($validatedData);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function edit($id)
    {
        $row = ClientPaymentSetting::findOrFail($id);
        $paymentCategories = PaymentCategory::getCategoriesSelect();

        return view('Admin.CRUDS.client_payment_setting.parts.edit', compact('paymentCategories', 'row'));

    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'payment_category' => 'sometimes|required|integer|between:1,4',
            'month' => 'sometimes|required|integer|between:1,12',
            'title' => 'sometimes|required|string|max:255',
            'from_day' => 'sometimes|required|integer|between:1,31',
            'to_day' => 'sometimes|required|integer|between:1,31',
        ]);

        $row = ClientPaymentSetting::findOrFail($id);

        $row->update($validatedData);
        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function destroy(ClientPaymentSetting $clientPaymentSetting)
    {
        $clientPaymentSetting->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function getClientPaymentSetting(Request $request)
    {
        $client = Client::find($request->client_id);
        return response()->json(
            [
                'data' => ClientPaymentSetting::where('month', $request->month)
                    ->when(
                        $client?->payment_category,
                        fn($q) => $q->where('payment_category', $client?->payment_category)
                    )
                    ->get(),
            ]);

    }
}
