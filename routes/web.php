<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardAdminController;
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

    Route::get('/admin/users', [UserController::class, 'index']);
    Route::get('/admin/users/create', [UserController::class, 'create']);
    Route::post('/admin/users/store', [UserController::class, 'store']);
    Route::get('/admin/users/edit/{id}', [UserController::class, 'edit']);
    Route::put('/admin/users/{id}/update', [UserController::class, 'update']);
    Route::delete('/admin/users/delete/{id}', [UserController::class, 'destroy']);
    Route::get('/admin/users/data', [UserController::class, 'getUsersData'])->name('users.data');
    Route::post('/admin/users/send-qrcode-email', [UserController::class, 'sendQrCodeEmail'])->name('send.qrcode.email');
    // Other admin routes...
});