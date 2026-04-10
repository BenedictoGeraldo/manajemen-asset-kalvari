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
use App\Http\Controllers\PemeliharaanController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\MutasiAsetController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------

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
        Route::get('pengelola/{pengelola}', [MasterPengelolaController::class, 'show'])->name('pengelola.show')->middleware('permission:master.pengelola.view');
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

    // Rute untuk data transaksional
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('pembelian', [PembelianController::class, 'index'])->name('pembelian.index')->middleware('permission:transaksi.pembelian.view');
        Route::get('pembelian-export/{format}', [PembelianController::class, 'export'])->name('pembelian.export')->middleware('permission:transaksi.pembelian.view');
        Route::get('pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create')->middleware('permission:transaksi.pembelian.create');
        Route::post('pembelian', [PembelianController::class, 'store'])->name('pembelian.store')->middleware('permission:transaksi.pembelian.create');
        Route::get('pembelian/{pembelian}', [PembelianController::class, 'show'])->name('pembelian.show')->middleware('permission:transaksi.pembelian.view');
        Route::get('pembelian/{pembelian}/edit', [PembelianController::class, 'edit'])->name('pembelian.edit')->middleware('permission:transaksi.pembelian.edit');
        Route::put('pembelian/{pembelian}', [PembelianController::class, 'update'])->name('pembelian.update')->middleware('permission:transaksi.pembelian.edit');
        Route::delete('pembelian/{pembelian}', [PembelianController::class, 'destroy'])->name('pembelian.destroy')->middleware('permission:transaksi.pembelian.delete');
        Route::post('pembelian/{pembelian}/approve', [PembelianController::class, 'approve'])->name('pembelian.approve')->middleware('permission:transaksi.pembelian.approve');

        Route::get('peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index')->middleware('permission:transaksi.peminjaman.view');
        Route::get('peminjaman-export/{format}', [PeminjamanController::class, 'export'])->name('peminjaman.export')->middleware('permission:transaksi.peminjaman.export');
        Route::get('peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create')->middleware('permission:transaksi.peminjaman.create');
        Route::post('peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store')->middleware('permission:transaksi.peminjaman.create');
        Route::get('peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show')->middleware('permission:transaksi.peminjaman.view');
        Route::get('peminjaman/{peminjaman}/edit', [PeminjamanController::class, 'edit'])->name('peminjaman.edit')->middleware('permission:transaksi.peminjaman.edit');
        Route::put('peminjaman/{peminjaman}', [PeminjamanController::class, 'update'])->name('peminjaman.update')->middleware('permission:transaksi.peminjaman.edit');
        Route::delete('peminjaman/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy')->middleware('permission:transaksi.peminjaman.delete');
        Route::post('peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve')->middleware('permission:transaksi.peminjaman.approve');
        Route::post('peminjaman/{peminjaman}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject')->middleware('permission:transaksi.peminjaman.approve');
        Route::get('peminjaman/{peminjaman}/handover', [PeminjamanController::class, 'handoverForm'])->name('peminjaman.handover.form')->middleware('permission:transaksi.peminjaman.handover');
        Route::post('peminjaman/{peminjaman}/handover', [PeminjamanController::class, 'handover'])->name('peminjaman.handover')->middleware('permission:transaksi.peminjaman.handover');
        Route::get('peminjaman/{peminjaman}/return', [PeminjamanController::class, 'returnForm'])->name('peminjaman.return.form')->middleware('permission:transaksi.peminjaman.return');
        Route::post('peminjaman/{peminjaman}/return', [PeminjamanController::class, 'returnAssets'])->name('peminjaman.return')->middleware('permission:transaksi.peminjaman.return');

        Route::get('pemeliharaan', [PemeliharaanController::class, 'index'])->name('pemeliharaan.index')->middleware('permission:transaksi.pemeliharaan.view');
        Route::get('pemeliharaan-export/{format}', [PemeliharaanController::class, 'export'])->name('pemeliharaan.export')->middleware('permission:transaksi.pemeliharaan.export');
        Route::get('pemeliharaan/create', [PemeliharaanController::class, 'create'])->name('pemeliharaan.create')->middleware('permission:transaksi.pemeliharaan.create');
        Route::post('pemeliharaan', [PemeliharaanController::class, 'store'])->name('pemeliharaan.store')->middleware('permission:transaksi.pemeliharaan.create');
        Route::get('pemeliharaan/{pemeliharaan}', [PemeliharaanController::class, 'show'])->name('pemeliharaan.show')->middleware('permission:transaksi.pemeliharaan.view');
        Route::get('pemeliharaan/{pemeliharaan}/edit', [PemeliharaanController::class, 'edit'])->name('pemeliharaan.edit')->middleware('permission:transaksi.pemeliharaan.edit');
        Route::put('pemeliharaan/{pemeliharaan}', [PemeliharaanController::class, 'update'])->name('pemeliharaan.update')->middleware('permission:transaksi.pemeliharaan.edit');
        Route::delete('pemeliharaan/{pemeliharaan}', [PemeliharaanController::class, 'destroy'])->name('pemeliharaan.destroy')->middleware('permission:transaksi.pemeliharaan.delete');
        Route::post('pemeliharaan/{pemeliharaan}/approve', [PemeliharaanController::class, 'approve'])->name('pemeliharaan.approve')->middleware('permission:transaksi.pemeliharaan.approve');
        Route::post('pemeliharaan/{pemeliharaan}/reject', [PemeliharaanController::class, 'reject'])->name('pemeliharaan.reject')->middleware('permission:transaksi.pemeliharaan.approve');
        Route::post('pemeliharaan/{pemeliharaan}/process', [PemeliharaanController::class, 'startProcess'])->name('pemeliharaan.process')->middleware('permission:transaksi.pemeliharaan.process');
        Route::get('pemeliharaan/{pemeliharaan}/complete', [PemeliharaanController::class, 'completeForm'])->name('pemeliharaan.complete.form')->middleware('permission:transaksi.pemeliharaan.complete');
        Route::post('pemeliharaan/{pemeliharaan}/complete', [PemeliharaanController::class, 'complete'])->name('pemeliharaan.complete')->middleware('permission:transaksi.pemeliharaan.complete');

        Route::get('mutasi-aset', [MutasiAsetController::class, 'index'])->name('mutasi_aset.index')->middleware('permission:transaksi.mutasi_aset.view');
        Route::get('mutasi-aset-export/{format}', [MutasiAsetController::class, 'export'])->name('mutasi_aset.export')->middleware('permission:transaksi.mutasi_aset.export');
        Route::get('mutasi-aset/create', [MutasiAsetController::class, 'create'])->name('mutasi_aset.create')->middleware('permission:transaksi.mutasi_aset.create');
        Route::post('mutasi-aset', [MutasiAsetController::class, 'store'])->name('mutasi_aset.store')->middleware('permission:transaksi.mutasi_aset.create');
        Route::get('mutasi-aset/{mutasi_aset}', [MutasiAsetController::class, 'show'])->name('mutasi_aset.show')->middleware('permission:transaksi.mutasi_aset.view');
        Route::get('mutasi-aset/{mutasi_aset}/edit', [MutasiAsetController::class, 'edit'])->name('mutasi_aset.edit')->middleware('permission:transaksi.mutasi_aset.edit');
        Route::put('mutasi-aset/{mutasi_aset}', [MutasiAsetController::class, 'update'])->name('mutasi_aset.update')->middleware('permission:transaksi.mutasi_aset.edit');
        Route::delete('mutasi-aset/{mutasi_aset}', [MutasiAsetController::class, 'destroy'])->name('mutasi_aset.destroy')->middleware('permission:transaksi.mutasi_aset.delete');
        Route::post('mutasi-aset/{mutasi_aset}/approve', [MutasiAsetController::class, 'approve'])->name('mutasi_aset.approve')->middleware('permission:transaksi.mutasi_aset.approve');
        Route::post('mutasi-aset/{mutasi_aset}/reject', [MutasiAsetController::class, 'reject'])->name('mutasi_aset.reject')->middleware('permission:transaksi.mutasi_aset.approve');
        Route::post('mutasi-aset/{mutasi_aset}/process', [MutasiAsetController::class, 'process'])->name('mutasi_aset.process')->middleware('permission:transaksi.mutasi_aset.process');
        Route::get('mutasi-aset/{mutasi_aset}/complete', [MutasiAsetController::class, 'completeForm'])->name('mutasi_aset.complete.form')->middleware('permission:transaksi.mutasi_aset.complete');
        Route::post('mutasi-aset/{mutasi_aset}/complete', [MutasiAsetController::class, 'complete'])->name('mutasi_aset.complete')->middleware('permission:transaksi.mutasi_aset.complete');
    });

    // Rute untuk laporan
    Route::prefix('laporan')->name('laporan.')->middleware('laporan.access')->group(function () {
        Route::get('data-aset', [LaporanController::class, 'dataAset'])->name('data-aset.index');
        Route::get('data-aset-export/{format}', [LaporanController::class, 'exportDataAset'])->name('data-aset.export');
        Route::get('mutasi-aset', [LaporanController::class, 'mutasiAset'])->name('mutasi-aset.index');
        Route::get('mutasi-aset-export/{format}', [LaporanController::class, 'exportMutasiAset'])->name('mutasi-aset.export');
        Route::get('pembelian', [LaporanController::class, 'pembelian'])->name('pembelian.index');
        Route::get('pembelian-export/{format}', [LaporanController::class, 'exportPembelian'])->name('pembelian.export');
    });

    // Rute untuk logout
    Route::post('logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');
});

