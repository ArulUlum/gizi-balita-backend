<?php

use App\Http\Controllers\DataAnakController;
use App\Http\Controllers\AnakController;
use App\Http\Controllers\StatistikAnakController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DataAnakController::class, 'show'])->middleware(['auth'])->name('dashboard');

Route::get('/input-anak', function () {
    return view('input-anak');
})->middleware(['auth'])->name('input-anak');
Route::post('/input-anak', [DataAnakController::class, 'store'])->middleware(['auth'])->name('input-anak');
Route::post('/input-anak-orang-tua', [DataAnakController::class, 'storeOrangTua'])->middleware(['auth'])->name('input-anak-orang-tua');

Route::get('/detail-anak/{id}', [AnakController::class, 'show'])->middleware(['auth'])->name('detail-anak');

Route::get('/input-statistik/{id}', [StatistikAnakController::class, 'show'])->middleware(['auth'])->name('input-statistik');

Route::post('/input-statistik/{id}', [StatistikAnakController::class, 'store'])->middleware(['auth'])->name('input-statistik');

require __DIR__.'/auth.php';

Route::prefix('command')->group(function () {
    Route::get('migrate', function () {
        Artisan::call('migrate');
    });
    Route::get('seed', function () {
        Artisan::call('db:seed --class RoleSeeder');
    });
});
