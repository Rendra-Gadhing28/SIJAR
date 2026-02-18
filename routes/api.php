<?php
use App\Http\Controllers\Mobile\ItemMobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group( function () {
    Route::get('/test', [ItemMobile::class, 'index']);
});



?>