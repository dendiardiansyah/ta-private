<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PenarikanPoinController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\PoinController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminKatalogController;
use App\Http\Controllers\Admin\AdminTransaksiController;
use App\Http\Controllers\Petugas\PetugasTransaksiController;

Route::get('/', function () {
    return view('common.welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        $query = request()->query();
        $query['auth'] = 'login';

        return redirect('/?' . http_build_query($query));
    })->name('login');

    Route::get('/register', function () {
        $query = request()->query();
        $query['auth'] = 'register';

        return redirect('/?' . http_build_query($query));
    })->name('register');
});

// Route untuk dashboard yang hanya bisa diakses oleh pengguna yang sudah terautentikasi
Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [BeritaController::class, 'index'])->name('dashboard');
    Route::get('/image-proxy', [BeritaController::class, 'imageProxy'])->name('image.proxy');
    Route::get('/poin', [PoinController::class, 'index'])->name('poin');
    Route::get('/penarikan-poin', [PenarikanPoinController::class, 'index'])->name('penarikan');
    Route::post('/penarikan-poin', [PenarikanPoinController::class, 'store'])->name('penarikan.store');
});

Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');

// Admin routes (previously mislabeled as "pelaku usaha")
Route::prefix('admin')->name('admin.')->middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
    'role:admin',
])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/transaksi', [AdminTransaksiController::class, 'index'])->name('transaksi');
    Route::put('/transaksi/{transaksi_id}', [AdminTransaksiController::class, 'update'])->name('transaksi.update');

    Route::get('/katalog', [AdminKatalogController::class, 'index'])->name('katalog');
    Route::get('/katalog/create', [AdminKatalogController::class, 'create'])->name('katalog.create');
    Route::post('/katalog/store', [AdminKatalogController::class, 'store'])->name('katalog.store');
    Route::get('/katalog/edit/{jenis_sampah_id}', [AdminKatalogController::class, 'edit'])->name('katalog.edit');
    Route::put('/katalog/edit/{jenis_sampah_id}', [AdminKatalogController::class, 'update'])->name('katalog.update');
    Route::delete('/katalog/delete/{jenis_sampah_id}', [AdminKatalogController::class, 'destroy'])->name('katalog.delete');
});

// Petugas routes
Route::prefix('petugas')->name('petugas.')->middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
    'role:petugas',
])->group(function () {
    Route::get('/', [PetugasTransaksiController::class, 'index'])->name('index');
    Route::put('/{transaksi_id}', [PetugasTransaksiController::class, 'update'])->name('update');
});


// Route untuk transaksi
Route::middleware([
    'auth',
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

// Fallback Route untuk mengalihkan halaman yang tidak relevan (404 Not Found)
Route::fallback(function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->hasRole('petugas')) {
            return redirect()->route('petugas.index');
        }
        return redirect()->route('dashboard');
    }

    // Jika belum login, paksa ke halaman login
    return redirect()->route('login');
});
