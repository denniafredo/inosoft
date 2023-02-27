<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MotorController;

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

Route::controller(MotorController::class)->group(function () {
    Route::get('motors', 'index');
    Route::post('motors', 'store');
    Route::get('motors/{id}', 'show');
    Route::put('motors/{id}', 'update');
    Route::delete('motors/{id}', 'destroy');
}); 