<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPeminjamanController;
use App\Http\Controllers\Admin\AdminItemController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
   
    
    // atau bisa juga ditulis lengkap seperti ini:
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::get('/peminjaman/{id}/edit', [PeminjamanController::class, 'edit'])->name('peminjaman.edit');
    Route::put('/peminjaman/{id}', [PeminjamanController::class, 'update'])->name('peminjaman.update');
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
    Route::post('/peminjaman/{id}', [PeminjamanController::class, 'selesai'])->name('peminjaman.selesai');
    //home page
    Route::get('/homepage', [peminjamanController::class, 'beranda'])->name('user.homepage');
    // BARANG
    Route::get('/barang', [ItemController::class, 'index'])->name('barang.index');
    
    // RIWAYAT
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

    // Route::get('/peminjaman', [PeminjamanController::class, 'create'])
    // ->name('peminjaman.create');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'gantiPassw'])->name('password.update');
})->middleware('user');


// ADMIN ROUTES
// Admin Panel
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

   // Dashboard
    Route::get('/dashboard', [AdminPeminjamanController::class, 'dashboard'])->name('dashboard'); // admin.dashboard

    // Kelola Barang
    // Hapus ' admin.admin.' yang berlebihan
    Route::get('/barang', [AdminItemController::class, 'index'])->name('barang.index'); // admin.barang.index
    Route::get('/barang/create', [AdminItemController::class, 'create'])->name('barang.create'); // admin.barang.create
    Route::post('/barang', [AdminItemController::class, 'store'])->name('barang.store'); // admin.barang.store
    Route::get('/barang/{id}/edit', [AdminItemController::class, 'edit'])->name('barang.edit'); // admin.barang.edit
    Route::put('/barang/{id}', [AdminItemController::class, 'update'])->name('barang.update'); // admin.barang.update
    Route::delete('/barang/{id}', [AdminItemController::class, 'destroy'])->name('barang.destroy'); // admin.barang.destroy

    // Kelola Peminjaman User
    // Hapus ' admin.admin.' yang berlebihan
    Route::get('/peminjaman', [AdminPeminjamanController::class, 'index'])->name('peminjaman.index'); // admin.peminjaman.index
    Route::get('/peminjaman/{id}', [AdminPeminjamanController::class, 'show'])->name('peminjaman.show'); // admin.peminjaman.show
    Route::post('/peminjaman/{id}/approve', [AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve'); // admin.peminjaman.approve
    Route::post('/peminjaman/{id}/reject', [AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject'); // admin.peminjaman.reject

    // Pindahkan rute notifikasi ke sini, dan perbaiki penamaannya:
     Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminPeminjamanController::class, 'notifications'])->name('index');
        Route::post('/{id}/read', [AdminPeminjamanController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [AdminPeminjamanController::class, 'markAllAsRead'])->name('markAllRead');
    });
})->middleware('admin');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        Route::put('/password', [ProfileController::class, 'gantiPassw'])->name('password.update');
    });
   
    // ... rute admin lainnya

    

// ITEM IMAGES (Public access untuk menampilkan gambar)
Route::get('/item/image/{filename}', [ItemController::class, 'showImage'])->name('item.image');
Route::get('/item-image/{filename}', [ItemController::class, 'showImage'])->name('image.show');

// ITEM (jika berbeda dengan barang)
Route::get('/item', [ItemController::class, 'index'])->name('items.index');

require __DIR__ . '/auth.php';