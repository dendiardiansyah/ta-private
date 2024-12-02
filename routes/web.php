<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PenjemputanController;

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

// Route untuk transaksi
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Menampilkan form transaksi
    Route::get('/penjemputan/create', [TransaksiController::class, 'create'])->name('penjemputan.create');

    // Menyimpan transaksi
    Route::post('/transaksi/store', [TransaksiController::class, 'store'])->name('transaksi.store');
});
