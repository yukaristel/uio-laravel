<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\KategoriMenuController;
use App\Http\Controllers\MenuMakananController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AsetTetapController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\PembelianBahanController;
use App\Http\Controllers\TopupGameController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Bahan Baku
    Route::resource('bahan-baku', BahanBakuController::class);

    // Pembelian Bahan
    Route::resource('pembelian-bahan', PembelianBahanController::class)->only(['index', 'create', 'store', 'show']);

    // Kategori Menu
    Route::resource('kategori-menu', KategoriMenuController::class)->except(['show']);

    // Menu Makanan
    Route::post('menu-makanan/generate-hpp', [MenuMakananController::class, 'generateHpp'])->name('menu-makanan.generate-hpp');
    Route::delete('menu-makanan/{menu_makanan}/resep/{resep}', [MenuMakananController::class, 'destroyResep'])->name('menu-makanan.resep.destroy');
    Route::resource('menu-makanan', MenuMakananController::class);

    // Transaksi Penjualan
    Route::resource('transaksi', TransaksiController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('transaksi/{transaksi}/struk', [TransaksiController::class, 'struk'])->name('transaksi.struk');

    // Stock
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('movement', [StockController::class, 'movement'])->name('movement');
        Route::get('opname', [StockController::class, 'opname'])->name('opname');
        Route::post('opname', [StockController::class, 'storeOpname'])->name('opname.store');
    });

    // Top-Up Game
    Route::resource('topup-game', TopupGameController::class)->only(['index', 'create', 'store', 'show']);

    // Aset Tetap
    Route::resource('aset-tetap', AsetTetapController::class);
    Route::post('aset-tetap/{asetTetap}/penyusutan', [AsetTetapController::class, 'penyusutan'])->name('aset-tetap.penyusutan');
    Route::post('aset-tetap/{asetTetap}/lepas', [AsetTetapController::class, 'lepas'])->name('aset-tetap.lepas');

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('harian', [LaporanController::class, 'harian'])->name('harian');
        Route::get('bulanan', [LaporanController::class, 'bulanan'])->name('bulanan');
        Route::get('stok', [LaporanController::class, 'stok'])->name('stok');
        Route::get('neraca', [LaporanController::class, 'neraca'])->name('neraca');
        Route::get('labarugi', [LaporanController::class, 'labarugi'])->name('labarugi');
        Route::get('jurnal', [LaporanController::class, 'jurnal'])->name('jurnal-transaksi');
    });

    // Karyawan (admin only)
    Route::resource('karyawan', KaryawanController::class);

    // Jurnal Umum
    Route::resource('jurnal', JurnalController::class)->except(['show']);

    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
