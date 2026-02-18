<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemMobile extends Controller
{
    public function index(){
        $item = Item::with('kategori_jurusan')->lazy();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diambil',
            'data' => $item
        ]);
    }    
}
