<?php
use App\Http\Controllers\Admin\UnduLaporan;
use App\Http\Controllers\api\auth\LoginController;
use App\Http\Controllers\api\auth\LogoutController;
use App\Http\Controllers\WEB\UserController;
use App\Http\Controllers\WEB\waktuPembelajaran;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WEB\ProfileController;
use App\Http\Controllers\WEB\PeminjamanController;
use App\Http\Controllers\WEB\ItemController;
use App\Http\Controllers\WEB\AdminPeminjamanController;
use App\Http\Controllers\Admin\AdminItemController;
use App\Http\Controllers\Admin\ActivityLoggerController;
use App\Http\Controllers\Admin\NotificationController; 


Route::prefix('test')->group( function () {
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman-kirim', [PeminjamanController::class, 'store'])->name('peminjaman.store')->middleware('throttle:60,1');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::get('/peminjaman/{id}/edit', [PeminjamanController::class, 'edit'])->name('peminjaman.edit');
    Route::put('/peminjaman/{id}', [PeminjamanController::class, 'update'])->name('peminjaman.update');
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
    Route::post('/peminjaman/{id}', [PeminjamanController::class, 'selesai'])->name('peminjaman.selesai');
    //home page
    Route::get('/homepage', [PeminjamanController::class, 'beranda'])->name('user.homepage');
    Route::get('/waktu', [waktuPembelajaran::class, 'index'])->name('waktu.pembelajaran');
    Route::get('/jurusan', [UserController::class, 'getKategori'])->name('kategori.jurusan');
    Route::get('/barang', [ItemController::class, 'index'])->name('barang.index')->middleware('auth:sanctum');

    //landing page
    Route::get('/landing', [ItemController::class, 'LandingPage']);
    // PROFILE
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update')->middleware('throttle:10,1');
    Route::delete('/profile/destroy/{id}', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'gantiPassw'])->name('password.update');

    Route::prefix('auth')->group( function () {
        Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('api.login');
        Route::post('/logout', [LogoutController::class, 'destroy'])->name('api.logout');
    })->middleware('web');;
    // Route::middleware(['throttle:5,1'])->group( function () {
    //     Route::post('/login', [UserController::class, 'login']);
    // });
});




Route::middleware(['auth:sanctum','role:user'])->group(function () {
    // atau bisa juga ditulis lengkap seperti ini:
    Route::get('/peminjaman', [peminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [peminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store')->middleware('throttle:60,1');
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
    Route::put('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update')->middleware('throttle:10,1');
    Route::delete('/profile/destroy/{id}', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'gantiPassw'])->name('password.update');
});



Route::middleware(['auth:sanctum','role:admin'])->prefix('admin')->name('admin.')->group(function () {

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

    Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [ProfileController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [ProfileController::class, 'destroy'])->name('destroy');
    Route::put('/password', [ProfileController::class, 'gantiPassw'])->name('password.update');
});
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
    //unduh laporan admin
        Route::get('/laporan/data',[UnduLaporan::class, 'getData'])->name('laporan.data');
        Route::get('/laporan/excel',[UnduLaporan::class, 'exportExcel'])->name('laporan.excel');
        Route::get('/laporan/pdf',[UnduLaporan::class, 'exportPDF'])->name('laporan.pdf');
   
});



?>