<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group([
    'middleware' => ['api-cors', 'json.response', 'lang'],
], function () {

    Route::post('send-code', [AuthController::class, 'sendCode']);
    Route::post('activate-account', [AuthController::class, 'activate']);

    Route::get('user-types', [HomeController::class, 'userTypes']);

    //register
    Route::post('register', [AuthController::class, 'register']);

    Route::post('login', [AuthController::class, 'login']);

    // Webhook for Paymennt

    Route::group(['middleware' => ['OptionalSanctumMiddleware']], function () {

        Route::get('home', [HomeController::class, 'index']);
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {});
});
