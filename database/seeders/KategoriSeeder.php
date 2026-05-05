<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


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

        foreach ($kategori as $kat) {
            Kategori::firstOrCreate([
                'nama_kategori' => $kat['nama_kategori'],
                'icon' => $kat['icon'],
                'role' => $kat['role'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
