<?php

use App\Http\Controllers\Api\AnakController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\DesaController;
use App\Http\Controllers\Api\OrangTuaController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PosyanduController;
use App\Http\Controllers\Api\StatistikAnakController;
use App\Http\Controllers\Api\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::middleware('auth:sanctum')->get('test', function () {
    $user = Auth::user();
    return response()->json($user, 200);
});

Route::get('desa', [DesaController::class, 'index']);
Route::post('desa', [DesaController::class, 'store']);
Route::delete('desa/{id}', [DesaController::class, 'destroy']);

Route::get('posyandu', [PosyanduController::class, 'index']);
Route::post('posyandu', [PosyanduController::class, 'store']);
Route::delete('posyandu/{id}', [PosyanduController::class, 'destroy']);

// START: Endpoint Comment
Route::get('comment', [CommentController::class, 'index']);
Route::post('comment', [CommentController::class, 'store']);
Route::get('comment/{id}', [CommentController::class, 'show']);
Route::delete('comment/{id}', [CommentController::class, 'destroy']);
// END: Endpoint Comment

// START: Endpoint Post
Route::post('post', [PostController::class, 'store']);
Route::get('post', [PostController::class, 'index']);
Route::get('post/orang-tua/{id}', [PostController::class, 'showByOrangTua']);
Route::get('post/{id}', [PostController::class, 'show']);
Route::put('post/{id}', [PostController::class, 'update']);
Route::delete('post/{id}', [PostController::class, 'destroy']);
// END: Endpoint Post

Route::get('export-data-anak-csv', [AnakController::class, 'exportDataAnakCSV']);

Route::prefix("orang-tua")->middleware('auth:sanctum')->group(function () {
    Route::get('data-anak/{id}', [AnakController::class, 'show']);
    Route::post('data-anak', [AnakController::class, 'storeWithOrangTua']);
    Route::get('data-anak', [AnakController::class, 'indexWithOrangTua']);
    Route::put('data-anak/{id}', [AnakController::class, 'update']);

    Route::post('statistik-anak', [StatistikAnakController::class, 'store']);
    Route::get('statistik-anak/{statistikAnak}', [StatistikAnakController::class, 'show']);
    Route::put('statistik-anak/{id}', [StatistikAnakController::class, 'update']);
});

Route::prefix("posyandu")->middleware('auth:sanctum')->group(function () {
    Route::get('orang-tua', [OrangTuaController::class, 'index']);
    Route::post('data-anak', [AnakController::class, 'storeWithKaderPosyandu']);
    Route::get('data-anak', [AnakController::class, 'indexWithKaderPosyandu']);
    Route::get('data-anak/{id}', [AnakController::class, 'show']);
    Route::put('data-anak/{id}', [AnakController::class, 'update']);
    Route::delete('data-anak/{id}', [AnakController::class, 'destroy']);

    Route::post('statistik-anak', [StatistikAnakController::class, 'store']);
    Route::get('statistik-anak/{statistikAnak}', [StatistikAnakController::class, 'show']);
    Route::put('statistik-anak/{id}', [StatistikAnakController::class, 'update']);
    Route::delete('statistik-anak/{id}', [StatistikAnakController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->post('upload-image', [UploadController::class, 'store']);
Route::middleware('auth:sanctum')->get('image/{fileName}', [UploadController::class, 'index']);

/* Route::prefix('orang-PosyanduControlleriddleware('auth:sanctum')->group(function () { */
/*     Route::post('data-anak', []) */
/* }); */
