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
use App\Http\Controllers\Admin\ActivityLoggerController;
use App\Http\Controllers\Admin\NotificationController; // TAMBAHKAN INI
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth','role:user'])->group(function () {
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

  

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'gantiPassw'])->name('password.update');
})->middleware('user');



Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminPeminjamanController::class, 'dashboard'])->name('dashboard'); // admin.dashboard
    Route::get('/barang', [AdminItemController::class, 'index'])->name('barang.index'); // admin.barang.index
    Route::get('/barang/create', [AdminItemController::class, 'create'])->name('barang.create'); // admin.barang.create
    Route::post('/barang', [AdminItemController::class, 'store'])->name('barang.store'); // admin.barang.store
    Route::get('/barang/{id}/edit', [AdminItemController::class, 'edit'])->name('barang.edit'); // admin.barang.edit
    Route::put('/barang/{id}', [AdminItemController::class, 'update'])->name('barang.update'); // admin.barang.update
    Route::delete('/barang/{id}', [AdminItemController::class, 'destroy'])->name('barang.destroy'); // admin.barang.destroy
    Route::put('/barang/{id}/tersedia', [AdminItemController::class, 'setTersedia'])->name('barang.setTersedia');
    Route::put('/barang/{id}/rusak', [AdminItemController::class, 'setRusak'])->name('barang.setRusak');

    // Peminjaman
    Route::get('/peminjaman', [AdminPeminjamanController::class, 'index'])->name('peminjaman.index'); // admin.peminjaman.index
    Route::get('/peminjaman/{id}', [AdminPeminjamanController::class, 'show'])->name('peminjaman.show'); // admin.peminjaman.show
    Route::post('/peminjaman/{id}/approve', [AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve'); // admin.peminjaman.approve
    Route::post('/peminjaman/{id}/reject', [AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject'); // admin.peminjaman.reject
    Route::post('/peminjaman/{id}/selesai', [AdminPeminjamanController::class, 'selesai'])->name('peminjaman.selesai'); // admin.peminjaman.selesai

    // Activity Logger
    Route::get('/activitylogger', [ActivityLoggerController::class, 'index'])->name('activitylogger.index');
    Route::get('/activitylogger/{activityLogger}', [ActivityLoggerController::class, 'show'])->name('activitylogger.show');
    Route::delete('/activitylogger/{activityLogger}', [ActivityLoggerController::class, 'destroy'])->name('activitylogger.destroy');
    Route::post('/activitylogger/clear', [ActivityLoggerController::class, 'clearOldLogs'])->name('activitylogger.clear');

    // NOTIFICATION ROUTES - PERBAIKI DENGAN YANG BARU
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/trashed', [NotificationController::class, 'trashed'])->name('trashed');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('markAllRead');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [NotificationController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force', [NotificationController::class, 'forceDelete'])->name('forceDelete');
        Route::post('/clear-trash', [NotificationController::class, 'clearTrash'])->name('clearTrash');
        Route::post('/mass-action', [NotificationController::class, 'massAction'])->name('massAction');
    });
})->middleware('admin');

Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    Route::put('/password', [ProfileController::class, 'gantiPassw'])->name('password.update');
});

// ITEM IMAGES (Public access untuk menampilkan gambar)
Route::get('/item/image/{filename}', [ItemController::class, 'showImage'])->name('item.image');
Route::get('/item-image/{filename}', [ItemController::class, 'showImage'])->name('image.show');

// ITEM (jika berbeda dengan barang)
Route::get('/item', [ItemController::class, 'index'])->name('items.index');

require __DIR__ . '/auth.php';