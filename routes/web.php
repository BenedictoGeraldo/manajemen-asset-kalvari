<?php

use Illuminate\Support\Facades\Route;
// Mengimpor semua controller yang dibutuhkan
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataAsetController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda mendaftarkan rute web untuk aplikasi Anda. Rute-rute ini
| dimuat oleh RouteServiceProvider dan semuanya akan
| ditugaskan ke grup middleware "web".
|
*/

// Rute dasar akan dialihkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Grup rute untuk tamu (pengguna yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'login']);
});

// Grup rute untuk pengguna yang sudah diautentikasi
Route::middleware('auth')->group(function () {
    // Rute untuk dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('data-aset', DataAsetController::class)->except(['show']);

    // Rute untuk logout
    Route::post('logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');
});

