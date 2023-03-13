<?php

use App\Http\Controllers\Api\Auth\RegisterUserController;
use App\Http\Controllers\Api\Auth\AuthenticateUserController;
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

Route::get('testing', function () {
    $resp = [
        "code" => 200,
        "message" => "ok",
    ];

    return response()->json($resp, 200);
});

Route::prefix("orang-tua")->group(function () {
    Route::post('register', [RegisterUserController::class, 'registerOrangTua']);
    Route::post('login', [AuthenticateUserController::class, 'loginOrangTua']);
});

Route::prefix("posyandu")->group(function () {
    Route::post('register', [RegisterUserController::class, 'registerKaderPosyandu']);
    Route::post('login', [AuthenticateUserController::class, 'loginKaderPosyandu']);
});

Route::prefix("desa")->group(function () {
    Route::post('register', [RegisterUserController::class, 'registerDesa']);
    Route::post('login', [AuthenticateUserController::class, 'loginDesa']);
});

Route::prefix("tenaga-kesehatan")->group(function () {
    Route::post('register', [RegisterUserController::class, 'registerTenagaKesehatan']);
    Route::post('login', [AuthenticateUserController::class, 'loginTenagaKesehatan']);
});

Route::prefix("admin")->group(function () {
    Route::post('register', [RegisterUserController::class, 'registerAdmin']);
    Route::post('login', [AuthenticateUserController::class, 'loginAdmin']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
