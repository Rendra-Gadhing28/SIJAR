<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\item;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    public function index(){
        $peminjaman = Peminjaman::where("user_id", Auth::id())
            ->with(["barang", "jam"])
            ->latest()
            ->paginate(10);
        return view('user.pinjam', compact("peminjaman"));
    }

    public function show($id){
        //
    }

    public function create(){
        //
    }

    public function store(Request $request){
        //
    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){
        //
    }

    public function destroy($id){
        //
    }
}
