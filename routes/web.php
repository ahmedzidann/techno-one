<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/',function (){
 return redirect()->route('admin.index');
})->name('frontend.index');

Route::get('/run-command/{command}', function ($command, Request $request) {
    // List of allowed commands to prevent unauthorized commands from being executed
    $allowedCommands = [
        'migrate',
        'db:seed'
    ];

    if (in_array($command, $allowedCommands)) {
        $exitCode = Artisan::call($command);
        return response()->json([
            'message' => 'Command executed successfully',
            'exitCode' => $exitCode,
            'output' => Artisan::output(),
        ]);
    } else {
        return response()->json([
            'error' => 'Command not allowed',
        ], 403);
    }
});
