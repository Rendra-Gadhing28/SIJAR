<?php

namespace App\Http\Controllers\WEB;

use App\Models\waktu_pembelajaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class waktuPembelajaran extends Controller
{
    public function index(){
        $waktuP = waktu_pembelajaran::all();
        return response()->json([
            "status" => true,
            "message" => "data waktu pembelajaran",
            "data" => $waktuP
        ], 200);
    }
}
