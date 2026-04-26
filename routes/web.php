<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PenarikanPoinController;
use App\Http\Controllers\PelakuUsahaController;
use App\Http\Controllers\PoinController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    Route::get('/image-proxy', [BeritaController::class, 'imageProxy'])->name('image.proxy');
    Route::get('/poin', [PoinController::class, 'index'])->name('poin');
    Route::get('/penarikan-poin', [PenarikanPoinController::class, 'index'])->name('penarikan');
    Route::post('/penarikan-poin', [PenarikanPoinController::class, 'store'])->name('penarikan.store');
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
    // Legacy URL: arahkan login pelaku usaha ke login utama dengan role preselect
    Route::get('/login', function () {
        return redirect()->route('login', ['role' => 'pelaku_usaha']);
    })->name('pelaku_usaha.login');

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
        'role:pelaku_usaha',
    ])->group(function () {
        Route::get('/dashboard', [PelakuUsahaController::class, 'showDashboard'])->name('pelaku_usaha.dashboard');
        Route::get('/katalog', [PelakuUsahaController::class, 'index'])->name('pelaku_usaha.katalog');
        Route::get('/katalog/edit/{jenis_sampah_id}', [PelakuUsahaController::class, 'editKatalog'])->name('pelaku_usaha.katalog.edit');
        Route::put('/katalog/edit/{jenis_sampah_id}', [PelakuUsahaController::class, 'updateKatalog'])->name('pelaku_usaha.katalog.update');
        Route::delete('/katalog/delete/{jenis_sampah_id}', [PelakuUsahaController::class, 'deleteKatalog'])->name('pelaku_usaha.katalog.delete');
        Route::post('/katalog/store', [PelakuUsahaController::class, 'addKatalog'])->name('pelaku_usaha.katalog.store');
        Route::get('/katalog/create', [PelakuUsahaController::class, 'createKatalog'])->name('pelaku_usaha.katalog.create');
        Route::get('/transaksi', [PelakuUsahaController::class, 'showTransaksi'])->name('pelaku_usaha.transaksi');
        Route::put('/transaksi/{transaksi_id}', [PelakuUsahaController::class, 'update'])->name('pelaku_usaha.transaksi.update');

        // Alias logout khusus pelaku usaha (pakai guard web yang sama)
        Route::post('/logout', function (Request $request) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        })->name('pelaku_usaha.logout');
    });
});
