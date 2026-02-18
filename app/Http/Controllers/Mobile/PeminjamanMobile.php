<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WEB\peminjamanController;
use Illuminate\Http\Request;

class PeminjamanMobile extends Controller
{
    public function store(Request $req){
        $req->validate([
            'keperluan' => 'required|string|max:255',
            'item_id'=> 'required|exists:item,id',
            'kode_unit' => 'nullable|string',
            'waktu_ids' => 'required|array|min:1',
            'waktu_ids.*' => 'string|exists:waktu,id',
            'bukti' => 'required|image|mimes:jpeg, jpg|max:2048'
        ],[
            'keperluan.required' => 'Keperluan harus diisi.',
            'item_id.required' => 'Item harus dipilih.',
            'item_id.exists' => 'Item yang dipilih tidak valid.',
            'kode_unit.string' => 'Kode unit harus berupa teks.',
            'waktu_ids.required' => 'Waktu peminjaman harus dipilih.',
            'waktu_ids.array' => 'Waktu peminjaman harus berupa array.',
            'waktu_ids.min' => 'Pilih minimal satu waktu peminjaman.',
            'waktu_ids.*.string' => 'Setiap waktu peminjaman harus berupa teks.',
            'waktu_ids.*.exists' => 'Waktu peminjaman yang dipilih tidak valid.',
            'bukti.required' => 'Bukti peminjaman harus diunggah.',
            'bukti.image' => 'Bukti peminjaman harus berupa gambar.',
            'bukti.mimes' => 'Bukti peminjaman harus berformat JPEG atau JPG.',
            'bukti.max' => 'Ukuran bukti peminjaman maksimal 2MB.'
        ]);

        $foto = $req->file('bukti')->store('bukti_peminjaman', 'public');

        $jPelajaran = [];
        foreach($req->waktu_ids as $w){
            $jPelajaran[] = $w;
        }

        $controller = new peminjamanController();
        
        $create = $controller->create($req->all());
        $create->update([
            'bukti' => $foto,
            'waktu_ids' => json_encode($jPelajaran)
        ]);
    }
}
