<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PenjemputanController;
use App\Http\Controllers\PelakuUsahaController;
use App\Models\PelakuUsaha;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk dashboard yang hanya bisa diakses oleh pengguna yang sudah terautentikasi
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [BeritaController::class, 'index'])->name('dashboard');
});


Route::get('/penjemputan', function () {
    return view('penjemputan');
})->name('penjemputan');

Route::get('/katalog', [PelakuUsahaController::class, 'showKatalog'])->name('katalog');

// Route untuk transaksi
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Mengarahkan route /penjemputan ke /penjemputan/create
    Route::get('/penjemputan', [TransaksiController::class, 'create'])->name('penjemputan');

    // Route untuk halaman create penjemputan
    Route::get('/penjemputan/create', [TransaksiController::class, 'create'])->name('penjemputan.create');

    // Route untuk menyimpan transaksi
    Route::post('/penjemputan/store', [TransaksiController::class, 'store'])->name('transaksi.store');

    // Route untuk menampilkan riwayat penjemputan (read)
    Route::get('/penjemputan/history', [TransaksiController::class, 'history'])->name('penjemputan.history');

    Route::get('/penjemputan/{transaksi_id}/edit', [TransaksiController::class, 'edit'])->name('penjemputan.edit');
    Route::put('/penjemputan/{transaksi_id}', [TransaksiController::class, 'update'])->name('penjemputan.update');


    // Route untuk menghapus transaksi penjemputan
    Route::delete('/penjemputan/{transaksi_id}', [TransaksiController::class, 'destroy'])->name('penjemputan.destroy');
});


Route::prefix('pelaku-usaha')->group(function () {
    // Login dan logout pelaku usaha
    Route::get('/login', [PelakuUsahaController::class, 'showLoginForm'])->name('pelaku_usaha.login');
    Route::post('/login', [PelakuUsahaController::class, 'login']);
    Route::get('/transaksi', [PelakuUsahaController::class, 'showTransaksi'])->name('pelaku_usaha.transaksi');
    Route::put('/transaksi/{transaksi_id}', [PelakuUsahaController::class, 'update'])->name('pelaku_usaha.transaksi.update');


    // Dashboard pelaku usaha (butuh autentikasi pelaku usaha)
    Route::middleware('auth:pelaku_usaha')->group(function () {
        Route::get('/dashboard', [PelakuUsahaController::class, 'showDashboard'])->name('pelaku_usaha.dashboard');
        Route::get('/pelaku-usaha/katalog', [PelakuUsahaController::class, 'index'])->name('pelaku_usaha.katalog');
        Route::get('/pelaku-usaha/katalog/edit/{jenis_sampah_id}', [PelakuUsahaController::class, 'editKatalog'])->name('pelaku_usaha.katalog.edit');
        Route::put('/pelaku-usaha/katalog/edit/{jenis_sampah_id}', [PelakuUsahaController::class, 'updateKatalog'])->name('pelaku_usaha.katalog.update');
        Route::delete('/pelaku-usaha/katalog/delete/{jenis_sampah_id}', [PelakuUsahaController::class, 'deleteKatalog'])->name('pelaku_usaha.katalog.delete');
        Route::post('/pelaku-usaha/katalog/store', [PelakuUsahaController::class, 'addKatalog'])->name('pelaku_usaha.katalog.store');
        Route::get('/pelaku-usaha/katalog/create', [PelakuUsahaController::class, 'createKatalog'])->name('pelaku_usaha.katalog.create');
        Route::post('/logout', [PelakuUsahaController::class, 'logout'])->name('pelaku_usaha.logout');
    });
});
