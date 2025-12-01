<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jurusan;
use App\Models\Kategori;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusans = [
            ['nama_jurusan' => 'PPLG 1', 'nama_kategori' => 'PPLG'],
            ['nama_jurusan' => 'PPLG 2', 'nama_kategori' => 'PPLG'],
            ['nama_jurusan' => 'PPLG 3', 'nama_kategori' => 'PPLG'],
            ['nama_jurusan' => 'TJKT 1', 'nama_kategori' => 'TJKT'],
            ['nama_jurusan' => 'TJKT 2', 'nama_kategori' => 'TJKT'],
            ['nama_jurusan' => 'DKV 1', 'nama_kategori' => 'DKV'],
            ['nama_jurusan' => 'DKV 2', 'nama_kategori' => 'DKV'],
            ['nama_jurusan' => 'DKV 3', 'nama_kategori' => 'DKV'],
            ['nama_jurusan' => 'LK 1', 'nama_kategori' => 'LK'],
            ['nama_jurusan' => 'LK 2', 'nama_kategori' => 'LK'],
            ['nama_jurusan' => 'PS 1', 'nama_kategori' => 'PS'],
            ['nama_jurusan' => 'PS 2', 'nama_kategori' => 'PS'],
            ['nama_jurusan' => 'Admin PPLG', 'nama_kategori' => 'PPLG'],
            ['nama_jurusan' => 'Admin TJKT', 'nama_kategori' => 'TJKT'],
            ['nama_jurusan' => 'Admin PS', 'nama_kategori' => 'PS'],
            ['nama_jurusan' => 'Admin LK', 'nama_kategori' => 'LK'],
            ['nama_jurusan' => 'Admin DKV', 'nama_kategori' => 'DKV']
        ];

      foreach ($jurusans as $jurusan) {
        $kategori = Kategori::where(['nama_kategori' => $jurusan['nama_kategori']])->firstOrCreate();
        Jurusan::create([
            'nama_jurusan' => $jurusan['nama_jurusan'],
            'kategori_id' => $kategori->id]);
        }
    }
}
