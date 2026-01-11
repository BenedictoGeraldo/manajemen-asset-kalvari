<?php

use Illuminate\Support\Facades\Route;
// Mengimpor semua controller yang dibutuhkan
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataAsetKolektifController;
use App\Http\Controllers\MasterKategoriController;
use App\Http\Controllers\MasterLokasiController;
use App\Http\Controllers\MasterKondisiController;
use App\Http\Controllers\MasterPengelolaController;
use App\Http\Controllers\UserManagementController;

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('permission:dashboard.view');

    // Rute untuk data aset kolektif
    Route::get('data-aset', [DataAsetKolektifController::class, 'index'])->name('data-aset.index')->middleware('permission:data-aset.view');
    Route::get('data-aset/create', [DataAsetKolektifController::class, 'create'])->name('data-aset.create')->middleware('permission:data-aset.create');
    Route::post('data-aset', [DataAsetKolektifController::class, 'store'])->name('data-aset.store')->middleware('permission:data-aset.create');
    Route::get('data-aset/{data_aset}', [DataAsetKolektifController::class, 'show'])->name('data-aset.show')->middleware('permission:data-aset.view');
    Route::get('data-aset/{data_aset}/edit', [DataAsetKolektifController::class, 'edit'])->name('data-aset.edit')->middleware('permission:data-aset.edit');
    Route::put('data-aset/{data_aset}', [DataAsetKolektifController::class, 'update'])->name('data-aset.update')->middleware('permission:data-aset.edit');
    Route::delete('data-aset/{data_aset}', [DataAsetKolektifController::class, 'destroy'])->name('data-aset.destroy')->middleware('permission:data-aset.delete');
    Route::get('data-aset-export/{format}', [DataAsetKolektifController::class, 'export'])->name('data-aset.export')->middleware('permission:data-aset.export');

    // Rute untuk master data
    Route::prefix('master')->name('master.')->group(function () {
        // Master Kategori
        Route::get('kategori', [MasterKategoriController::class, 'index'])->name('kategori.index')->middleware('permission:master.kategori.view');
        Route::get('kategori/create', [MasterKategoriController::class, 'create'])->name('kategori.create')->middleware('permission:master.kategori.create');
        Route::post('kategori', [MasterKategoriController::class, 'store'])->name('kategori.store')->middleware('permission:master.kategori.create');
        Route::get('kategori/{kategori}/edit', [MasterKategoriController::class, 'edit'])->name('kategori.edit')->middleware('permission:master.kategori.edit');
        Route::put('kategori/{kategori}', [MasterKategoriController::class, 'update'])->name('kategori.update')->middleware('permission:master.kategori.edit');
        Route::delete('kategori/{kategori}', [MasterKategoriController::class, 'destroy'])->name('kategori.destroy')->middleware('permission:master.kategori.delete');
        Route::get('kategori-export/{format}', [MasterKategoriController::class, 'export'])->name('kategori.export')->middleware('permission:master.kategori.view');

        // Master Lokasi
        Route::get('lokasi', [MasterLokasiController::class, 'index'])->name('lokasi.index')->middleware('permission:master.lokasi.view');
        Route::get('lokasi/create', [MasterLokasiController::class, 'create'])->name('lokasi.create')->middleware('permission:master.lokasi.create');
        Route::post('lokasi', [MasterLokasiController::class, 'store'])->name('lokasi.store')->middleware('permission:master.lokasi.create');
        Route::get('lokasi/{lokasi}/edit', [MasterLokasiController::class, 'edit'])->name('lokasi.edit')->middleware('permission:master.lokasi.edit');
        Route::put('lokasi/{lokasi}', [MasterLokasiController::class, 'update'])->name('lokasi.update')->middleware('permission:master.lokasi.edit');
        Route::delete('lokasi/{lokasi}', [MasterLokasiController::class, 'destroy'])->name('lokasi.destroy')->middleware('permission:master.lokasi.delete');
        Route::get('lokasi-export/{format}', [MasterLokasiController::class, 'export'])->name('lokasi.export')->middleware('permission:master.lokasi.view');

        // Master Kondisi
        Route::get('kondisi', [MasterKondisiController::class, 'index'])->name('kondisi.index')->middleware('permission:master.kondisi.view');
        Route::get('kondisi/create', [MasterKondisiController::class, 'create'])->name('kondisi.create')->middleware('permission:master.kondisi.create');
        Route::post('kondisi', [MasterKondisiController::class, 'store'])->name('kondisi.store')->middleware('permission:master.kondisi.create');
        Route::get('kondisi/{kondisi}/edit', [MasterKondisiController::class, 'edit'])->name('kondisi.edit')->middleware('permission:master.kondisi.edit');
        Route::put('kondisi/{kondisi}', [MasterKondisiController::class, 'update'])->name('kondisi.update')->middleware('permission:master.kondisi.edit');
        Route::delete('kondisi/{kondisi}', [MasterKondisiController::class, 'destroy'])->name('kondisi.destroy')->middleware('permission:master.kondisi.delete');
        Route::get('kondisi-export/{format}', [MasterKondisiController::class, 'export'])->name('kondisi.export')->middleware('permission:master.kondisi.view');

        // Master Pengelola
        Route::get('pengelola', [MasterPengelolaController::class, 'index'])->name('pengelola.index')->middleware('permission:master.pengelola.view');
        Route::get('pengelola/create', [MasterPengelolaController::class, 'create'])->name('pengelola.create')->middleware('permission:master.pengelola.create');
        Route::post('pengelola', [MasterPengelolaController::class, 'store'])->name('pengelola.store')->middleware('permission:master.pengelola.create');
        Route::get('pengelola/{pengelola}/edit', [MasterPengelolaController::class, 'edit'])->name('pengelola.edit')->middleware('permission:master.pengelola.edit');
        Route::put('pengelola/{pengelola}', [MasterPengelolaController::class, 'update'])->name('pengelola.update')->middleware('permission:master.pengelola.edit');
        Route::delete('pengelola/{pengelola}', [MasterPengelolaController::class, 'destroy'])->name('pengelola.destroy')->middleware('permission:master.pengelola.delete');
        Route::get('pengelola-export/{format}', [MasterPengelolaController::class, 'export'])->name('pengelola.export')->middleware('permission:master.pengelola.view');
    });

    // Rute untuk user management
    Route::get('user-management', [UserManagementController::class, 'index'])->name('user-management.index')->middleware('permission:user-management.view');
    Route::get('user-management/create', [UserManagementController::class, 'create'])->name('user-management.create')->middleware('permission:user-management.create');
    Route::post('user-management', [UserManagementController::class, 'store'])->name('user-management.store')->middleware('permission:user-management.create');
    Route::get('user-management/{user}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit')->middleware('permission:user-management.edit');
    Route::put('user-management/{user}', [UserManagementController::class, 'update'])->name('user-management.update')->middleware('permission:user-management.edit');
    Route::delete('user-management/{user}', [UserManagementController::class, 'destroy'])->name('user-management.destroy')->middleware('permission:user-management.delete');

    // Rute untuk logout
    Route::post('logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');
});

