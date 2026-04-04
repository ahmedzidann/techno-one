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
use DB;

class SettingController extends Controller
{

    public function contact_us(Request $request)
    {

        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => ['required', 'regex:/^01[0-9]{9}$/'],
            'type'    => 'required',
            'subject' => 'required',
            'message' => 'required',
        ], [
            'phone.regex' => 'رقم الهاتف يجب أن يكون 11 رقم ويبدأ بـ 01',
        ]);


        $id = DB::table('contacts')->insertGetId([
            'name'       => $data['name'],
            'phone'      => $data['phone'],
            'type'       => $data['type'],
            'subject'    => $data['subject'],
            'message'    => $data['message'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'تم إرسال الرسالة بنجاح',
            'data'    => [
                'id' => $id
            ]
        ], 201);
    }

    public function static_page(Request $request)
    {
        // التحقق من وجود type في الريكوست
        $data = $request->validate([
            'page' => 'required|string'
        ]);

        // جلب الصفحة حسب الـ type القادم من POST
        $page = DB::table('static_pages')
            ->where('page', $data['page'])
            ->first();

        // لو مش موجود
        if (!$page) {
            return response()->json([
                'status'  => false,
                'message' => 'الصفحة غير موجودة',
            ], 404);
        }

        // إرجاع البيانات
        return response()->json([
            'status'  => true,
            'message' => 'تم جلب البيانات بنجاح',
            'data'    => $page
        ]);
    }



    public function Pay_methods()
    {
        $slider = DB::table('client_payment_settings')->get();

        return response()->json([
            'status' => true,
            'message' => 'طرق الدفع',
            'data' =>$slider
        ]);
    }

    public function check_version(Request $request)
    {
        $data = $request->validate([
            'version' => 'required'
        ]);

        if($request->version == '7.5')
        {
            return response()->json([
            'status' => true,
            'message' => 'انت علي اخر نسخه محدثه',
            'data' =>null
        ]); 
        }else{
             return response()->json([
            'status' => false,
            'message' => 'هناك تحديث جديد',
            'data' =>null
        ]);
        }

    }
    
}
