<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
// use Illuminate\Support\Facades\Storage;


use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'PPLG', 'role' => 'user', 'icon' => 'icon/pplg.png',], 
            ['nama_kategori' => 'LK', 'role' => 'user', 'icon' => 'icon/lk1.png',],
            ['nama_kategori' => 'TJKT', 'role' => 'user', 'icon' => 'icon/tjkt.png'],
            ['nama_kategori' => 'DKV', 'role' => 'user', 'icon' => 'icon/dkv.png'],
            ['nama_kategori' => 'PS', 'role' => 'user', 'icon' => 'icon/ps.png'],
        ];

        $tryPath = '';
        if (Storage::disk('public')->exists($tryPath)){
            $origin = $tryPath;
            $encryptedName = Crypt::encryptString($tryPath);
            $gambarPath = "encrypted/{$encryptedName}";
            Storage::disk('public')->copy($origin, $gambarPath);
            $encrypt = $encryptedName;
             // Keluar dari kedua loop jika gambar ditemukan
        }
        foreach ($kategori as $kat=> $data) {
            Kategori::firstOrCreate([
                'nama_kategori' => $data['nama_kategori'],
                'icon' => $encrypt,
                'role' => $data['role'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
