<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\PurchasesDetails;
use App\Models\SalesDetails;
use App\Models\PreviewCategory;
use App\Models\PreviewProduct;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB ;
use Illuminate\Support\Facades\Storage;

class PreviewController extends Controller
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
            $rows = PreviewProduct::query()->with(['category']);
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

        return view('Admin.CRUDS.previews.index');
    }

    public function create()
    {
        $categories = Category::get();
        $categories = PreviewCategory::get();
       
        return view('Admin.CRUDS.previews.parts.create', compact('categories'));
    }



public function store(Request $request)
{
    // Validate كل الحقول
    $validated = $request->validate([
        'name' => 'required|unique:products,name',
        'points' => 'required',
        'preview_category_id' => 'required',
        'path_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
    ]);


    try {
        DB::beginTransaction(); // بدء الـ transaction

        // تجهيز البيانات للمنتج فقط (بدون details)
        // $productData = collect($validated)->only([
        //     'code', 'name', 'audience_price', 'limit_for_request', 'limit_for_sale', 
        //     'unit_id', 'category_id', 'path_image'
        // ])->toArray();

        $validated['publisher'] = auth('admin')->user()->id;

        // حفظ الصورة لو موجودة
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('previews', 'public');
            $validated['image'] = $path;
        }

        // إنشاء المنتج
        PreviewProduct::create($validated);

        DB::commit(); // لو كله تمام نعمل commit

        return response()->json([
            'code' => 200,
            'message' => 'تمت العملية بنجاح!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack(); // لو حصل خطأ نرجع كل حاجة
        return response()->json([
            'code' => 500,
            'message' => 'حدث خطأ أثناء حفظ المنتج والتفاصيل: ' . $e->getMessage()
        ], 500);
    }
}

    public function edit($id)
    {
       $row = PreviewProduct::with('category')->findOrFail($id); 
        $categories = PreviewCategory::get();
        return view('Admin.CRUDS.previews.parts.edit', compact('categories','row'));

    }

  public function update(Request $request, $id)
{
    $product = PreviewProduct::findOrFail($id);

    // Validate كل الحقول
    $validated = $request->validate([
        'name' => 'required|unique:products,name',
        'points' => 'required',
        'preview_category_id' => 'required',
        'path_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
    ]);

   
    // Transaction عشان لو فشل أي شيء يرجع
    \DB::beginTransaction();

    try {
        // تجهيز البيانات للمنتج فقط (بدون details)
       
 $productData= $validated;
        // حفظ الصورة لو موجودة
        if ($request->hasFile('image')) {
            // احذف الصورة القديمة لو موجودة
            if ($product->image && \Storage::disk('public')->exists($product->image)) {
                \Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('previews', 'public');
            $productData['image'] = $path;
        }

        $productData['publisher'] = auth('admin')->user()->id;

        // تحديث المنتج
        $product->update($productData);


        \DB::commit();

        return response()->json([
            'code' => 200,
            'message' => 'تم تحديث المنتج بنجاح!'
        ]);

    } catch (\Exception $e) {
        \DB::rollBack();

        return response()->json([
            'code' => 500,
            'message' => 'حدث خطأ أثناء التحديث: ' . $e->getMessage()
        ], 500);
    }
}

public function destroy($id)
{
    DB::beginTransaction();

    try {

        $row = PreviewProduct::findOrFail($id);

        // حذف الصورة لو موجودة
        if ($row->image && Storage::disk('public')->exists($row->image)) {
            Storage::disk('public')->delete($row->image);
        }

       
        // حذف المنتج
        $row->delete();

        DB::commit();

        return response()->json([
            'code' => 200,
            'message' => 'تم حذف المنتج بنجاح!'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'code' => 500,
            'message' => 'حدث خطأ أثناء الحذف'
        ], 500);
    }
}

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
            $buyPrice = Product::where('id', $request->id)->first()->one_buy_price;
        }

        $salePrice = 0;
        if ($productFromSales && $productFromSales->amount != 0) {
            $salePrice = $productFromSales->total / $productFromSales->amount;
        } else {
            $salePrice = Product::where('id', $request->id)->first()->one_sell_price;
        }

        return response()->json(
            [
                'code' => 200,
                'buy_price' => $buyPrice,
                'sell_price' => $salePrice,
            ]);
    }

}
