<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            'PPLG',
            'LK',
            'TJKT',
            'DKV',
            'PS',
            'admin'
        ];

        foreach ($kategori as $kat) {
            \DB::table('kategori_jurusan')->insert([
                'nama_kategori' => $kat,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
