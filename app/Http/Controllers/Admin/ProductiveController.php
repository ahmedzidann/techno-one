<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Productive;
use App\Models\PurchasesDetails;
use App\Models\SalesDetails;
use App\Models\Unite;
use App\Models\ZonesSetting;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:عرض الاصناف,admin')->only('index');
        $this->middleware('permission:تعديل الاصناف,admin')->only(['edit', 'update']);
        $this->middleware('permission:إنشاء الاصناف,admin')->only(['create', 'store']);
        $this->middleware('permission:حذف الاصناف,admin')->only('destroy');
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = Productive::query()->with(['unit', 'category']);
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

            // ->add('company', function ($row) {
            //      return $row->company->title;

            // })

                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);

        }

        return view('Admin.CRUDS.productive.index');
    }

    public function create()
    {
        $categories = Category::get();
        $unites = Unite::get();
        $zones = ZonesSetting::whereNull('parent_id')->get();

        return view('Admin.CRUDS.productive.parts.create', compact('categories', 'unites', 'zones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required',
            'name' => 'required|unique:productive,name',
            'audience_price' => 'required|numeric',
            'limit_for_request' => 'required|numeric',
            'limit_for_sale' => 'required|numeric',
            'unit_id' => 'required|exists:unites,id',
            'category_id' => 'required|exists:categories,id',
           // 'company_id' => 'required|exists:companies,id',
           // 'shape_id' => 'required|exists:shapes,id',
           // 'zones_setting_id' => 'sometimes|exists:zones_settings,id',
            'path_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'

        ]);
   
      if ($request->hasFile('image')) {
          $path = $request->file('image')->store('products', 'public');
          $data['path_image'] = $path;   // يخزن فقط المسار داخل قاعدة البيانات
        }
        $data['publisher'] = auth('admin')->user()->id;
        Productive::create($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function edit($id)
    {
        $row = Productive::find($id);
      
        $categories = Category::get();
        $unites = Unite::get();
        $zones = ZonesSetting::whereNull('parent_id')->get();
        $city = ZonesSetting::where('id', $row->zones_setting_id)->first();
        return view('Admin.CRUDS.productive.parts.edit', compact('row', 'categories', 'unites', 'zones', 'city'));

    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'code' => 'required',
            'name' => 'required|unique:productive,name,' . $id,
            'audience_price' => 'required|numeric',
            'limit_for_request' => 'required|numeric',
            'limit_for_sale' => 'required|numeric',
            'unit_id' => 'required|exists:unites,id',
            'category_id' => 'required|exists:categories,id',
            //'company_id' => 'required|exists:companies,id',
            //'zones_setting_id' => 'sometimes|exists:zones_settings,id',
            'path_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'

        ]);
if ($request->hasFile('image')) {
          $path = $request->file('image')->store('products', 'public');
          $data['path_image'] = $path;   // يخزن فقط المسار داخل قاعدة البيانات
        }
        $row = Productive::find($id);

        $row->update($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function destroy($id)
    {

        $row = Productive::find($id);

        $row->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    } //end fun

    public function getPrice(Request $request)
    {

        $productFromPurchase = PurchasesDetails::where('productive_id', $request->id)
            ->when($request->batch_number, fn($q) => $q->where('batch_number', $request->batch_number))
            ->latest()
            ->first();

        $productFromSales = SalesDetails::where('productive_id', $request->id)
            ->when($request->batch_number, fn($q) => $q->where('batch_number', $request->batch_number))
            ->latest()
            ->first();

        $buyPrice = 0;
        if ($productFromPurchase && $productFromPurchase->amount != 0) {
            $buyPrice = $productFromPurchase->total / $productFromPurchase->amount;
        } else {
            $buyPrice = Productive::where('id', $request->id)->first()->one_buy_price;
        }

        $salePrice = 0;
        if ($productFromSales && $productFromSales->amount != 0) {
            $salePrice = $productFromSales->total / $productFromSales->amount;
        } else {
            $salePrice = Productive::where('id', $request->id)->first()->one_sell_price;
        }

        return response()->json(
            [
                'code' => 200,
                'buy_price' => $buyPrice,
                'sell_price' => $salePrice,
            ]);
    }

}
