<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/homepage', function () {
    return view('user.homepage');
})->middleware(['auth', 'verified'])->name('user.homepage');

Route::middleware('auth')->group(function () {
    Route::get("/peminjaman", [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get("/peminjaman/{id}", [PeminjamanController::class, 'show'])->name('peminjaman.show');

    Route::get("/barang", [ItemController::class, 'index'])->name('barang.index');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'gantiPassw'])->name('password.update');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/items', [AdminController::class, 'index'])->name('admin.index');
});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get("/peminjaman/create", [PeminjamanController::class, 'create'])->name('peminjaman.create');
});

Route::get('/item/image/{filename}', [ItemController::class, 'showImage'])->name('item.image');


// Route::get('/barang', [ItemController::class, 'index'])->name('barang.index');
Route::get('/item', [ItemController::class, 'index'])->name('items.index');

// Route untuk menampilkan gambar terenkripsi
Route::get('/item-image/{filename}', [ItemController::class, 'showImage'])->name('image.show');
require __DIR__ . '/auth.php';
