<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Area;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Helpers\SmsHelper;
use App\Models\Otp;
 use Tymon\JWTAuth\Facades\JWTAuth;
 use App\Models\CouponsConvert;
 use App\Models\Category;
use App\Models\Productive;
 use DB ;

class ProductController extends Controller
{
   public function categories()
    {
        $categories = Category::get();

        return response()->json([
            'status' => true,
            'message' => 'قائمة الأقسام',
            'data' => $categories
        ]);
    }


    public function slider()
    {
        $slider = DB::table('sliders')->get();

        return response()->json([
            'status' => true,
            'message' => 'قائمة الأقسام',
            'data' =>$slider
        ]);
    }


 public function productsByCategory(Request $request, $category_id)
{
    $category = Category::find($category_id);

    if (!$category) {
        return response()->json([
            'status' => false,
            'message' => 'القسم غير موجود'
        ], 404);
    }

    $perPage = $request->get('per_page', 10);

    // بناء الاستعلام
    $query = Productive::where('category_id', $category_id);

    // فلتر البحث بالاسم
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $products = $query->paginate($perPage);

    return response()->json([
        'status' => true,
        'message' => 'منتجات القسم',
        'category' => [
            'id' => $category->id,
            'name' => $category->name,
        ],
        'data' => $products->items(),

        'pagination' => [
            'current_page' => $products->currentPage(),
            'last_page'    => $products->lastPage(),
            'per_page'     => $products->perPage(),
            'total'        => $products->total(),
        ]
    ]);
}


public function allProducts(Request $request)
{
    // عدد العناصر في كل صفحة
    $perPage = $request->input('per_page', 10);

    // الاستعلام
    $query = Productive::query();

    // بحث بالاسم أو بالكود
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('code', 'like', "%$search%");
        });
    }

    $products = $query->paginate($perPage);

    return response()->json([
        'status' => true,
        'message' => 'جميع المنتجات',
        'data' => $products->items(),

        'pagination' => [
            'current_page' => $products->currentPage(),
            'last_page'    => $products->lastPage(),
            'per_page'     => $products->perPage(),
            'total'        => $products->total(),
        ]
    ]);
}




}

















