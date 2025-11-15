<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Item;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
class itemLK extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $barang_LK = [
            'LK' => [
                ['nama' => 'Pispot', 'stok' => 8, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Bengkok', 'stok' => 10, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Bak Instumen', 'stok' => 10, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Pinset Cirugis', 'stok' => 10, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Pinset Anatomis', 'stok' => 10, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Gunting plester', 'stok' => 7, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Gunting klem', 'stok' => 7, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Gunting jaringan', 'stok' => 7, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Pantom kelamin pria', 'stok' => 3, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Pantom kelamin wanita', 'stok' => 3, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Pantom RJP', 'stok' => 1, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Gunting Korentang', 'stok' => 2, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Selimut', 'stok' => 10, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Sprei', 'stok' => 10, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Laken', 'stok' => 8, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Stik Laken', 'stok' => 8, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Perlak', 'stok' => 8, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Bed', 'stok' => 7, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Termometer', 'stok' => 8, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Nebulizer', 'stok' => 2, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Timbangan Badan', 'stok' => 2, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Pulse Oximeter', 'stok' => 1, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Kursi Roda', 'stok' => 1, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'WWZ', 'stok' => 5, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Ice Bag', 'stok' => 5, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Kasur Antidekubitus', 'stok' => 1, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Tabung oksigen', 'stok' => 2, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Tounge Spatel', 'stok' => 4, 'jenis' => 'Kesehatan','status' => 'tersedia'],
                ['nama' => 'Refleks Hammer', 'stok' => 2, 'jenis' => 'Kesehatan','status' => 'tersedia'],
            ]];

             foreach($barang_LK as $LK =>$list){
                $jurusan = Kategori::firstOrCreate(['nama_kategori' => $LK]);
                foreach ($list as $brg) {
                // 🔍 Cari gambar berdasar  kan nama file
                $namaAsli = $brg['nama'];
                $namaVariasi = [
                strtolower(str_replace(' ', '_', $namaAsli)),  // kabel_vga
                strtolower(str_replace(' ', '', $namaAsli)),   // kabelvga
                strtolower($namaAsli),                          // kabel vga
                str_replace(' ', '_', $namaAsli),               // Kabel_VGA
                str_replace(' ', '', $namaAsli),                // KabelVGA
                $namaAsli,                                       // Kabel VGA (original)
        ];
                $extensions = ['jpg', 'jpeg', 'png','webp','avif'];
                $gambarPath = '';
                $origin = '';
                
                foreach ($extensions as $ext) {
                    foreach($namaVariasi as $var)
                    $tryPath = "{$LK}/{$var}.{$ext}";
                    
                $encrypt = null; // Deklarasi di luar

    if (Storage::disk('public')->exists($tryPath)) {
    $origin = $tryPath;
    $content = Storage::disk('public')->get($tryPath);
    
    $extension = pathinfo($tryPath, PATHINFO_EXTENSION);
    $encrypt = hash('sha256', $content . time()) . '.' . $extension;
    
    // Simpan file
    Storage::disk('public')->put('encrypted/' . $encrypt, $content);
    
    break;
    };

                }
               
                 // 🔢 Buat unit individual untuk setiap stok
                for ($i = 1; $i <= $brg['stok']; $i++) {
                    // Format kode unit: PPLG-PROY-1001
                    $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $brg['nama']), 0, 20));
                    $kodeUnit = sprintf('%s-%s-%04d', $LK, $prefix, $i);
                    Item::create([
                        'nama_item' => $brg['nama'],
                        'kode_unit' => $kodeUnit, // UNIT INDIVIDUAL
                        'jenis_item' => $brg['jenis'],
                        'kategori_jurusan_id' => $jurusan->id,
                        'status_item' => $brg['status'],
                        'foto_barang' => $encrypt, // Semua unit punya gambar sama
                    ]);
                }
            } 
        }
    }
}

