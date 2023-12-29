<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardPegawaiController;
use App\Http\Controllers\Absensi_DatangController;
use App\Http\Controllers\UserController;

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

// Route::get('/', function () {
//     return view('Admin.index');
// });

Route::get('/', [LoginController::class, 'viewLogin'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
// Route::get('logout', [LoginController::class, 'logout']);

Route::middleware([checkRole::class.':admin'])->group(function () {
    Route::get('/admin', [DashboardAdminController::class, 'viewAdmin']);

    Route::prefix('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/create', [UserController::class, 'create']);
        Route::post('/users/store', [UserController::class, 'store']);
        Route::get('/users/edit/{id}', [UserController::class, 'edit']);
        Route::put('/users/{id}/update', [UserController::class, 'update']);
        Route::delete('/users/delete/{id}', [UserController::class, 'destroy']);
        Route::get('/users/data', [UserController::class, 'getUsersData'])->name('users.data');
        Route::post('/users/send-qrcode-email', [UserController::class, 'sendQrCodeEmail'])->name('send.qrcode.email');
    });
    Route::prefix('admin')->group(function () {
        Route::get('profiles', [UserController::class, 'profile']);
    });
    // Other admin routes...
});
Route::middleware([checkRole::class.':pegawai'])->group(function () {
    Route::get('/pegawai', [DashboardPegawaiController::class, 'ViewPegawai']);
    Route::prefix('pegawai')->group(function () {
        Route::get('/absensi', [Absensi_DatangController::class, 'index']);
    });
    // Other admin routes...
});