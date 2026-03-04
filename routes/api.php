<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SettingController;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});







Route::prefix('client')->group(function () {
     Route::post('register', [ClientController::class, 'register']);
     Route::post('login', [ClientController::class, 'login']);
     Route::post('refresh', [ClientController::class, 'refresh']);
     Route::get('governs', [ClientController::class, 'getGoverns']);
     Route::post('SendOtp', [ClientController::class, 'send_otp']);
     Route::post('VerifyOtp', [ClientController::class, 'verify_otp']);
     Route::post('ResetPassword', [ClientController::class, 'reset_password']);
    Route::get('categories', [ProductController::class, 'categories']);
    Route::get('categories/{id}/products', [ProductController::class, 'productsByCategory']);
    Route::get('slider', [ProductController::class, 'slider']);
    Route::match(['get', 'post'], 'products', [ProductController::class, 'allProducts']);
     Route::post('contact_us', [SettingController::class, 'contact_us']);
      Route::post('static_page', [SettingController::class, 'static_page']);


    // هنا تحديد guard 'client-api' مهم جدا
    Route::middleware(['auth:client-api'])->group(function () {
        Route::get('me', [ClientController::class, 'me']);
        Route::post('logout', [ClientController::class, 'logout']);
        Route::post('logout-all', [ClientController::class, 'logoutAll']);
        Route::post('update-profile', [ClientController::class, 'update_profile']);
        Route::get('/clients', [ClientController::class, 'get_clients']);
        Route::post('/coupons/convert', [ClientController::class, 'convert']);
        Route::post('coupons/history', [ClientController::class, 'history']);
         Route::get('/coupons/balance', [ClientController::class, 'balance']);
         
       
    });
});



