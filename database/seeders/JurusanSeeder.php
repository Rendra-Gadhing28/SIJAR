<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusans = [
            'PS',
            'TJKT',
            'PPLG',
            'DKV',
            'LK',
            'Lainnya'
        ];

        foreach ($jurusans as $nama_jurusan) {
            Jurusan::create(['nama_jurusan' => $nama_jurusan]);
        }
    }
}
