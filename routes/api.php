<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\TransaksiController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group([
    'middleware' => 'api',
], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('auth/login', 'login');
        Route::post('auth/register', 'register');
        Route::post('auth/logout', 'logout');
        Route::post('auth/refresh', 'refresh');
    });
    Route::controller(KendaraanController::class)->group(function () {
        Route::get('kendaraans', 'index');
        Route::get('kendaraans/stock', 'stock');
    }); 
    Route::controller(MotorController::class)->group(function () {
        Route::get('motors', 'index');
        Route::post('motors', 'store');
        Route::get('motors/{id}', 'show');
        Route::put('motors/{id}', 'update');
        Route::delete('motors/{id}', 'destroy');
    }); 
    Route::controller(MobilController::class)->group(function () {
        Route::get('mobils', 'index');
        Route::post('mobils', 'store');
        Route::get('mobils/{id}', 'show');
        Route::put('mobils/{id}', 'update');
        Route::delete('mobils/{id}', 'destroy');
    }); 
    Route::controller(TransaksiController::class)->group(function () {
        Route::post('transaksi', 'store');
        Route::get('transaksi/report', 'report');
    }); 

});
