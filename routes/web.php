<?php

use Illuminate\Support\Facades\Route;
// Pastikan Anda mengimpor controller yang benar
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 
    Route::post('logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');
});