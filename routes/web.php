<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PenarikanPoinController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\PoinController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminKatalogController;
use App\Http\Controllers\Admin\PointRateController;
use App\Http\Controllers\Petugas\PetugasTransaksiController;
use App\Http\Controllers\PelakuUsaha\DashboardController as PelakuUsahaDashboardController;
use App\Http\Controllers\PelakuUsaha\ProductController as PelakuUsahaProductController;
use App\Http\Controllers\ProductPurchaseController;

Route::get('/', function () {
    return view('common.welcome');
})->name('welcome');

// Login and Register routes - accessible to guests and redirect authenticated users to dashboard
Route::middleware(['guest', 'redirect.if.authenticated'])->group(function () {
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

    // Point rate settings
    Route::get('/settings/point-rate', [PointRateController::class, 'edit'])->name('settings.point-rate.edit');
    Route::put('/settings/point-rate', [PointRateController::class, 'update'])->name('settings.point-rate.update');

    // Jenis Sampah (Waste Type) CRUD - replaces Katalog Produk
    Route::get('/jenis-sampah', [\App\Http\Controllers\Admin\AdminJenisSampahController::class, 'index'])->name('jenis-sampah.index');
    Route::get('/jenis-sampah/create', [\App\Http\Controllers\Admin\AdminJenisSampahController::class, 'create'])->name('jenis-sampah.create');
    Route::post('/jenis-sampah', [\App\Http\Controllers\Admin\AdminJenisSampahController::class, 'store'])->name('jenis-sampah.store');
    Route::get('/jenis-sampah/{jenis_sampah}/edit', [\App\Http\Controllers\Admin\AdminJenisSampahController::class, 'edit'])->name('jenis-sampah.edit');
    Route::put('/jenis-sampah/{jenis_sampah}', [\App\Http\Controllers\Admin\AdminJenisSampahController::class, 'update'])->name('jenis-sampah.update');
    Route::delete('/jenis-sampah/{jenis_sampah}', [\App\Http\Controllers\Admin\AdminJenisSampahController::class, 'destroy'])->name('jenis-sampah.destroy');

    // Admin User Approval
    Route::get('/approvals', [\App\Http\Controllers\Admin\AdminUserApprovalController::class, 'index'])->name('approvals.index');
    Route::get('/approvals/{user}/approve', [\App\Http\Controllers\Admin\AdminUserApprovalController::class, 'approve'])->name('approvals.approve.link');
    Route::post('/approvals/{user}', [\App\Http\Controllers\Admin\AdminUserApprovalController::class, 'approve'])->name('approvals.approve');

    // Admin Users Management
    Route::get('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('users.destroy');
});

// Pelaku Usaha routes
Route::prefix('pelaku-usaha')->name('pelaku_usaha.')->middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
    'role:pelaku_usaha',
])->group(function () {
    Route::get('/', function () {
        return redirect()->route('pelaku_usaha.dashboard');
    })->name('home');

    Route::get('/dashboard', [PelakuUsahaDashboardController::class, 'index'])->name('dashboard');

    Route::get('/products', [PelakuUsahaProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [PelakuUsahaProductController::class, 'create'])->name('products.create');
    Route::post('/products', [PelakuUsahaProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [PelakuUsahaProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [PelakuUsahaProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [PelakuUsahaProductController::class, 'destroy'])->name('products.destroy');
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

    // Purchase products using points
    Route::post('/katalog/products/{product}/buy', [ProductPurchaseController::class, 'store'])->name('katalog.products.buy');
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
        if ($user->hasRole('pelaku_usaha')) {
            return redirect()->route('pelaku_usaha.dashboard');
        }
        return redirect()->route('dashboard');
    }

    // Jika belum login, paksa ke halaman login
    return redirect()->route('login');
});
