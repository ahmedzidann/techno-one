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
  use Illuminate\Validation\Rule;
  
 use DB ;

class ClientController extends Controller
{
    /**
     * تسجيل عميل جديد
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:clients,phone',
            'governorate_id' => 'required|exists:areas,id',
            'city_id' => 'required|exists:areas,id',
            'address' => 'nullable|string|max:500',
            'previous_indebtedness' => 'required|numeric|min:0',
            'region_id' => 'nullable',
            'client_type' => 'required|in:main,sub,technical',
            'register_type' => 'required|in:office,web,android,iphone',
            'password' => 'required|string|min:6|confirmed',
            'Longitude' => 'required',
            'Latitude' => 'required',
        ]);

              $lastCode = DB::table('clients')->max('id');
             
            $data['code'] = $lastCode ? $lastCode + 1 : 1;
            $data['password'] = bcrypt($data['password']);
            $data['status'] = 'pending';

        Client::create($data);

        return response()->json([
            'status' => true,
            'message' => 'تم انشاء الحساب بنجاح انتظر الموافقه من الشركه لتتمكن من تسجيل الدخول'
        ], 201);
    }

    /**
     * تسجيل الدخول (مع Device + Refresh Token)
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
            'device_name' => 'required',
            'device_id' => 'required',
        ]);

        $client = Client::where('phone', $request->phone)->first();

        if (!$client || !Hash::check($request->password, $client->password)) {
            return response()->json(['message' => 'بيانات الدخول غير صحيحة'], 401);
        }

        if ($client->status == 'pending') {
            return response()->json(['message' => 'الحساب غير مفعل الرجاء التواصل مع الشركه لتفعيل الحساب'], 403);
        }
        
        if ($client->status == 'refused') {
            return response()->json(['message' => 'تم رفض طلبك لانشاء حساب الرجاء التواصل مع الشركه لتفعيل الحساب'], 403);
        }

        // JWT Access Token
        $accessToken = auth('client-api')->login($client);
        $payload = auth('client-api')->payload();
        $jti = $payload->get('jti');

        // Refresh Token
        $refreshToken = Str::uuid()->toString();

        RefreshToken::create([
            'client_id' => $client->id,
            'access_jti' => $jti,
            'refresh_token' => $refreshToken,
            'device_name' => $request->device_name,
            'device_id' => $request->device_id,
            'expires_at' => Carbon::now()->addDays(14),
        ]);

        return response()->json([
            'status' => true,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => auth('client-api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * Refresh Token (Rotation)
     */
    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required'
        ]);

        $oldToken = RefreshToken::where('refresh_token', $request->refresh_token)
            ->where('revoked', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$oldToken) {
            return response()->json(['message' => 'Refresh token غير صالح'], 401);
        }

        // Revoke old token
        $oldToken->update(['revoked' => true]);

        $client = Client::find($oldToken->client_id);

        $newAccessToken = auth('client-api')->login($client);
        $payload = auth('client-api')->payload();
        $newJti = $payload->get('jti');

        $newRefreshToken = Str::uuid()->toString();

        RefreshToken::create([
            'client_id' => $client->id,
            'access_jti' => $newJti,
            'refresh_token' => $newRefreshToken,
            'device_name' => $oldToken->device_name,
            'device_id' => $oldToken->device_id,
            'expires_at' => Carbon::now()->addDays(14),
        ]);

        return response()->json([
            'access_token' => $newAccessToken,
            'refresh_token' => $newRefreshToken,
            'token_type' => 'bearer',
            'expires_in' => auth('client-api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * Logout جهاز واحد
     */
    public function logout(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required'
        ]);

        RefreshToken::where('refresh_token', $request->refresh_token)
            ->update(['revoked' => true]);

        auth('client-api')->logout();

        return response()->json(['message' => 'تم تسجيل الخروج']);
    }

    /**
     * Logout من كل الأجهزة
     */
    public function logoutAll()
    {
        $client = auth('client-api')->user();

        RefreshToken::where('client_id', $client->id)
            ->update(['revoked' => true]);

        auth('client-api')->logout();

        return response()->json(['message' => 'تم تسجيل الخروج من كل الأجهزة']);
    }

    /**
     * بيانات العميل الحالي
     */
    public function me()
    {
        return response()->json([
            'status' => true,
            'data' => auth('client-api')->user()
        ]);
    }
public function update_profile(Request $request)
{
    $client = auth('client-api')->user();

    $data = $request->validate([
        'name' => 'sometimes|required|string|max:255',

        'phone' => 'sometimes|required|string|unique:clients,phone,' . $client->id,

        'governorate_id' => 'sometimes|required|exists:areas,id',
        'city_id' => 'sometimes|required|exists:areas,id',
        'region_id' => 'sometimes|required|exists:areas,id',

        'address' => 'nullable|string|max:500',
        'previous_indebtedness' => 'sometimes|required|numeric|min:0',

        'Longitude' => 'nullable',
        'Latitude' => 'nullable',

        'password' => 'nullable|string|min:6|confirmed',
    ]);

    // لو فيه باسورد جديد
    if (isset($data['password'])) {
        $data['password'] = Hash::make($data['password']);
    }

    $client->update($data);

    return response()->json([
        'status' => true,
        'message' => 'تم تحديث البيانات بنجاح',
        'data' => $client->fresh()
    ]);
}
    /**
     * المحافظات / المدن / المناطق
     */
     
     public function getGoverns(Request $request)
    {
      

        if ($request->govern_id) {
            $data = Area::where('from_id', $request->govern_id)->get();
            $message = 'بيانات المدن';
        } elseif ($request->city_id) {
            $data = Area::where('from_id', $request->city_id)->get();
            $message = 'بيانات المناطق';
        } else {
            $data = Area::whereNull('from_id')->get();
            $message = 'بيانات المحافظات';
        }

        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

 public function send_otp(Request $request)
{
    $data = $request->validate([
         'phone' => 'required|string',
        'purpose' => 'required|in:register,reset_password,verify_phone',
    ]);
 $client = Client::where('phone', $request->phone)->first();
     if ($request->purpose === 'reset_password') {
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'رقم الهاتف غير مسجل'
            ], 404);
        }
        }elseif($request->purpose === 'register'){
        if ($client) {
            return response()->json([
                'status' => false,
                'message' => 'رقم الهاتف مسجل من قبل'
            ], 404);
        }

       }

    // منع الإرسال المتكرر
    $exists = Otp::where('phone', $data['phone'])
        ->where('used', false)
        ->where('expires_at', '>', now())
        ->first();

    if ($exists) {
        return response()->json([
            'status' => false,
            'message' => 'تم إرسال كود مسبقاً، حاول لاحقاً'
        ], 429);
    }

    $otp = rand(100000, 999999);

    Otp::create([
        'phone' => $data['phone'],
        'otp' => $otp,
        'purpose' => $data['purpose'],
        'expires_at' => Carbon::now()->addMinutes(1),
    ]);

  $result =  SmsHelper::send($data['phone'], "كود التحقق الخاص بك هو: $otp");
  if($result['status']==200){
 return response()->json([
        'status' => true,
        'message' => 'تم إرسال كود التحقق'
    ]);
  }else{
    return response()->json([
        'status' => false,
        'message' => 'لم يتم ارسال كود التحقق'
    ]);
  }
   
}

public function verify_otp(Request $request)
{
    $request->validate([
        'phone' => 'required|string',
        'otp' => 'required|string',
        'purpose' => 'required|in:register,reset_password',
    ]);

    $otp = Otp::where('phone', $request->phone)
        ->where('otp', $request->otp)
        ->where('purpose', $request->purpose)
        ->where('used', false)
        ->first();

    if (!$otp) {
        return response()->json([
            'status' => false,
            'message' => 'كود التحقق غير صحيح'
        ], 422);
    }

    if ($otp->expires_at->isPast()) {
        return response()->json([
            'status' => false,
            'message' => 'انتهت صلاحية كود التحقق'
        ], 422);
    }

    $otp->update(['used' => true]);

    return response()->json([
        'status' => true,
        'message' => 'تم تأكيد الكود بنجاح'
    ]);
}


public function reset_password(Request $request)
{
    $request->validate([
        'phone' => 'required|string',
        'password' => 'required|min:6|confirmed',
    ]);

    $otp = Otp::where('phone', $request->phone)
        ->where('purpose', 'reset_password')
        ->where('used', true)
        ->latest()
        ->first();

    if (!$otp) {
        return response()->json([
            'status' => false,
            'message' => 'لم يتم تأكيد رقم الهاتف'
        ], 403);
    }

    $client = Client::where('phone', $request->phone)->first();

    $client->update([
        'password' => Hash::make($request->password)
    ]);

    // إلغاء استخدام OTP بعد النجاح
    $otp->delete();

    return response()->json([
        'status' => true,
        'message' => 'تم إعادة تعيين كلمة المرور بنجاح'
    ]);
}

public function get_clients(Request $request)
{
    // المستخدم الحالي من الـ JWT
    $user = JWTAuth::parseToken()->authenticate();

    $perPage = $request->get('per_page', 10);

    $query = Client::orderBy('id', 'desc');

    // فلتر بالاسم
    if ($request->filled('name')) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    // فلتر بالموبايل
    if ($request->filled('phone')) {
        $query->where('phone', 'like', '%' . $request->phone . '%');
    }

    $clients = $query->paginate($perPage);

    return response()->json([
        'status' => true,
        'user' => [
            'id'   => $user->id,
            'name' => $user->name,
        ],
        'data' => $clients->items(),
        'pagination' => [
            'current_page' => $clients->currentPage(),
            'last_page'    => $clients->lastPage(),
            'per_page'     => $clients->perPage(),
            'total'        => $clients->total(),
        ]
    ]);
}




// public function convert(Request $request)
// {
//     // المستخدم الحالي من التوكن
//     $fromUser = JWTAuth::parseToken()->authenticate();

//     // Validation
//     $request->validate([
//         'to_user_id' => 'required|exists:clients,id|different:' . $fromUser->id,
//         'amount'     => 'required|numeric|min:1',
//         'notes'      => 'nullable|string',
//     ]);

//     // العميل المستلم
//     $toClient = Client::findOrFail($request->to_user_id);

//     // مثال تحقق رصيد
//     if ($request->amount > $this->getCouponBalance($fromUser->id)) 
// {
//         return response()->json([
//             'status'  => false,
//             'message' => 'رصيد الكوبونات غير كافي'
//         ], 422);
//     }
   
//     DB::beginTransaction();
//     try {

//         // تسجيل التحويل
//         $convert = CouponsConvert::create([
//             'from_user_id' => $fromUser->id,
//             'to_user_id'   => $toClient->id,
//             'amount'       => $request->amount,
//             'notes'        => $request->notes,
//             'status'        => 'approved',
//             'converted_at' => Carbon::now(),
//         ]);

//         DB::commit();

//         // جلب اسم المرسل والمستلم من جدول clients
//         $fromClient = Client::find($fromUser->id);

//         return response()->json([
//             'status'  => true,
//             'message' => 'تم التحويل بنجاح',
//             'data'    => [
//                 'id' => $convert->id,
//                 'amount' => $convert->amount,
//                 'notes' => $convert->notes,
//                 'converted_at' => $convert->converted_at,

//                 'from_user' => [
//                     'id'   => $fromClient->id ?? null,
//                     'name' => $fromClient->name ?? null,
//                 ],

//                 'to_user' => [
//                     'id'   => $toClient->id,
//                     'name' => $toClient->name,
//                 ],
//             ]
//         ]);

//     } catch (\Exception $e) {
//         DB::rollBack();

//         return response()->json([
//             'status'  => false,
//             'message' => 'خطأ في العمليه',
//             'error'   => $e->getMessage()
//         ], 500);
//     }
// }
public function convert(Request $request)
{
    // المستخدم الحالي من التوكن
    $fromUser = JWTAuth::parseToken()->authenticate();

    // Validation
    $request->validate([
    'to_user_id' => [
        'required',
        'different:' . $fromUser->id,

        Rule::when(
            $request->to_user_id != 0,
            ['exists:clients,id']
        ),
    ],

    'amount' => 'required|numeric|min:1',
    'notes'  => 'nullable|string',
]);
    


    // العميل المستلم
   $toClient = $request->to_user_id == 0 ? null : Client::findOrFail($request->to_user_id);
    
    // مثال تحقق رصيد
    if ($request->amount > $this->getCouponBalance($fromUser->id)) 
{
        return response()->json([
            'status'  => false,
            'message' => 'رصيد الكوبونات غير كافي'
        ], 422);
    }

    DB::beginTransaction();
    try {

        // تسجيل التحويل
        $convert = CouponsConvert::create([
            'from_user_id' => $fromUser->id,
            'to_user_id'   => $request->to_user_id == 0 ? 0 : $toClient->id,
            'amount'       => $request->amount,
            'notes'        => $request->notes,
            'status'        =>'approved',
            'converted_at' => Carbon::now(),
        ]);

        DB::commit();

        // جلب اسم المرسل والمستلم من جدول clients
        $fromClient = Client::find($fromUser->id);

        return response()->json([
            'status'  => true,
            'message' => 'تم التحويل بنجاح',
            'data'    => [
                'id' => $convert->id,
                'amount' => $convert->amount,
                'notes' => $convert->notes,
                'converted_at' => $convert->converted_at,

                'from_user' => [
                    'id'   => $fromClient->id ?? null,
                    'name' => $fromClient->name ?? null,
                ],

                'to_user' => [
                    'id'   => $request->to_user_id == 0 ? 0 : $toClient->id,
                    'name' => $request->to_user_id == 0 ? 'Techno-one' : $toClient->name,
                ],
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status'  => false,
            'message' => 'خطأ في العمليه',
            'error'   => $e->getMessage()
        ], 500);
    }
}

public function history(Request $request)
{
    $user = JWTAuth::parseToken()->authenticate();

    $perPage = $request->get('per_page', 10);

    $query = CouponsConvert::where(function ($q) use ($user) {
        // $q->where('status', 'approved')
        $q->where('from_user_id', $user->id)
          ->orWhere('to_user_id', $user->id);
    });
     $query->where('status', 'approved');

    // 🔹 فلتر من تاريخ
    if ($request->filled('date_from')) {
        $query->whereDate('converted_at', '>=', $request->date_from);
    }

    // 🔹 فلتر إلى تاريخ
    if ($request->filled('date_to')) {
        $query->whereDate('converted_at', '<=', $request->date_to);
    }

    $converts = $query
        ->orderByDesc('converted_at')
        ->paginate($perPage);

    $data = $converts->map(function ($item) use ($user) {

        if ($item->from_user_id == $user->id) {
            $type = 'decrease';
            $sign = '-';
            $otherUserId = $item->to_user_id;
        } else {
            $type = 'increase';
            $sign = '+';
            $otherUserId = $item->from_user_id;
        }

        if ($otherUserId == 0) {
            $otherName = 'techno-one';
        } else {
            $client = Client::find($otherUserId);
            $otherName = $client?->name ?? null;
        }

        return [
            'id' => $item->id,
            'type' => $type,
            'sign' => $sign,
            'amount' => $item->amount,
            'notes' => $item->notes,
            'converted_at' => $item->converted_at,

            'other_user' => [
                'id' => $otherUserId,
                'name' => $otherName
            ]
        ];
    });

    return response()->json([
        'status' => true,
        'data' => $data,
        'pagination' => [
            'current_page' => $converts->currentPage(),
            'last_page' => $converts->lastPage(),
            'per_page' => $converts->perPage(),
            'total' => $converts->total(),
        ]
    ]);
}

  public function balance()
{
    // المستخدم الحالي من التوكن
    $user = JWTAuth::parseToken()->authenticate();

    // احسب الرصيد
    $balance = $this->getCouponBalance($user->id);

    return response()->json([
        'status' => true,
        'message' => 'رصيد الكوبونات الحالي',
        'data' => [
            'user_id' => $user->id,
            'name' => $user->name,
            'coupon_balance' => $balance
        ]
    ]);
}

/**
 * دالة لحساب الرصيد الكلي للكوبونات
 */
private function getCouponBalance($userId)
{
    // الكوبونات المستلمة
    $received = CouponsConvert::where('to_user_id', $userId)->where('status','approved')
                              ->sum('amount');

    // الكوبونات المرسلة
    $sent = CouponsConvert::where('from_user_id', $userId)->where('status','approved')
                          ->sum('amount');

    return $received - $sent;
}

}
