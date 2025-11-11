<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/homepage', function () {
    return view('user.homepage');
})->middleware(['auth', 'verified'])->name('user.homepage');

Route::middleware('auth')->group(function () {
    Route::get("/peminjaman", [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get("/peminjaman{id}", [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::get("/barang", [ItemController::class, 'index'])->name('barang.index');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get("/peminjaman/create", [PeminjamanController::class, 'create'])->name('peminjaman.create');
});
require __DIR__ . '/auth.php';
