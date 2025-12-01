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
        ['nama_kategori' => 'PPLG', 'role' => 'user'],
        ['nama_kategori' => 'LK', 'role' => 'user'],
        ['nama_kategori' => 'TJKT', 'role' => 'user'],
        ['nama_kategori' => 'DKV', 'role' => 'user'],
        ['nama_kategori' => 'PS', 'role' => 'user'],
    ];

        foreach ($kategori as $kat) {
            Kategori::firstOrCreate([
                'nama_kategori' => $kat['nama_kategori'],
                'role' => $kat['role'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
