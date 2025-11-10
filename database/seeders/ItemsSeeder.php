<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Items;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class ItemsSeeder extends Seeder
{
    public function run(): void
    {
        $barang_barang = [
            'LK' => [
                ['nama' => 'Pispot', 'stok' => 8, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Bengkok', 'stok' => 10, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Bak Instumen', 'stok' => 10, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Pinset Cirugis', 'stok' => 10, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Pinset Anatomis', 'stok' => 10, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Gunting plester', 'stok' => 7, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Gunting klem', 'stok' => 7, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Gunting jaringan', 'stok' => 7, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Pantom kelamin pria', 'stok' => 3, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Pantom kelamin wanita', 'stok' => 3, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Pantom RJP', 'stok' => 1, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Gunting Korentang', 'stok' => 2, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Selimut', 'stok' => 10, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Sprei', 'stok' => 10, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Laken', 'stok' => 8, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Stik Laken', 'stok' => 8, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Perlak', 'stok' => 8, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Bed', 'stok' => 7, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Termometer', 'stok' => 8, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Nebulizer', 'stok' => 2, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Timbangan Badan', 'stok' => 2, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Pulse Oximeter', 'stok' => 1, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Kursi Roda', 'stok' => 1, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'WWZ', 'stok' => 5, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Ice Bag', 'stok' => 5, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Kasur Antidekubitus', 'stok' => 1, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Tabung oksigen', 'stok' => 2, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Tounge Spatel', 'stok' => 4, 'jenis' => 'Kesehatan','foto'=>''],
                ['nama' => 'Refleks Hammer', 'stok' => 2, 'jenis' => 'Kesehatan','foto'=>''],
            ],

            'PPLG' => [
                ['nama' => 'Proyektor', 'stok' => 5, 'jenis' => 'Elektronik','foto'=>''],
                ['nama' => 'Kunci Lab', 'stok' => 5, 'jenis' => 'Kunci','foto'=>''],
                ['nama' => 'Kabel VGA', 'stok' => 20, 'jenis' => 'Elektronik','foto'=>''],
                ['nama' => 'Converter HDMI', 'stok' => 10, 'jenis' => 'Elektronik','foto'=>''],
                ['nama' => 'Keyboard', 'stok' => 20, 'jenis' => 'Elektronik','foto'=>''],
                ['nama' => 'Mouse', 'stok' => 20, 'jenis' => 'Elektronik','foto'=>''],
                ['nama' => 'VR Oculus', 'stok' => 3, 'jenis' => 'Elektronik','foto'=>''],
                ['nama' => 'Set Obeng', 'stok' => 3, 'jenis' => 'Alat Praktik','foto'=>''],
                ['nama' => 'Lan Tester', 'stok' => 3, 'jenis' => 'Alat Praktik','foto'=>''],
            ],

            'DKV' => [
                ['nama' => 'Kamera', 'stok' => 3, 'jenis' => 'Elektronik','foto'=>''],
                ['nama' => 'Tripod', 'stok' => 3, 'jenis' => 'Alat Praktik','foto'=>''],
                ['nama' => 'Kamera Panggung', 'stok' => 1, 'jenis' => 'Elektronik','foto'=>''],
                ['nama' => 'Perangkat Alat Streaming', 'stok' => 1, 'jenis' => 'Elektronik','foto'=>''],
                ['nama' => 'Pensil Warna 1 Set', 'stok' => 1, 'jenis' => 'Alat Tulis','foto'=>''],
                ['nama' => 'Pensil Gambar 1 Set', 'stok' => 1, 'jenis' => 'Alat Tulis','foto'=>''],
                ['nama' => 'Cat Warna Full Set', 'stok' => 1, 'jenis' => 'Alat Tulis','foto'=>''],
                ['nama' => 'Alat Recording Audio 1 Set', 'stok' => 1, 'jenis' => 'Elektronik','foto'=>''],
                ['nama' => 'LCD', 'stok' => 2, 'jenis' => 'Elektronik','foto'=>''],
                ['nama' => 'Alat Press 1 Paket', 'stok' => 1, 'jenis' => 'Alat Praktik','foto'=>''],
                ['nama' => 'Press Sablon Kaos Full Set', 'stok' => 2, 'jenis' => 'Alat Praktik','foto'=>''],
                ['nama' => 'Pen Tablet', 'stok' => 8, 'jenis' => 'Alat Praktik','foto'=>''],
                ['nama' => 'Pen Display', 'stok' => 30  , 'jenis' => 'Alat Praktik','foto'=>''],
            ],

            'TJKT' => [
            ['nama' => 'HDMI Converter', 'stok' => 4, 'jenis' => 'Elektronik', 'foto' => ''],
            ['nama' => 'Webcam', 'stok' => 4, 'jenis' => 'Elektronik', 'foto' => ''],
            ['nama' => 'Roll Kabel', 'stok' => 5, 'jenis' => 'Elektronik', 'foto' => ''],
            ['nama' => 'Lan USB Adapter', 'stok' => 4, 'jenis' => 'Elektronik', 'foto' => ''],
            ['nama' => 'Obeng', 'stok' => 4, 'jenis' => 'Alat Praktik', 'foto' => ''],
            ],

            'PS' => [

            ]
        ];


        foreach ($barang_barang as $namaJurusan => $barangList) {
            $jurusan = Kategori::firstOrCreate(['nama_kategori' => $namaJurusan]);
           

            foreach ($barangList as $brg) {
                $path = Storage::disk('public')->files($jurusan);
            if(Storage::disk('public')->exists($path)){
                $gambar = Storage::disk('public')->get($path);
                $base = base64_encode($gambar);

                $encryp = Crypt::encryptString($base);
            }
            else{
                $encryp = null;
            }

                Items::create([
                    'nama_item' => $brg['nama'],
                    'jenis_item' => $brg['jenis'],
                    'stok_barang' => $brg['stok'],
                    'nama_kategori' => $jurusan->id,
                    'status_item' => 'tersedia',
                    'foto_barang' => $encryp,
                ]);
            }
        }
    }
}
