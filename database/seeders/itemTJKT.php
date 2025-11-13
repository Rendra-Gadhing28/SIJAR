<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Items;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class itemTJKT extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barang_TJKT = [  'TJKT' => [
                ['nama' => 'HDMI Converter', 'stok' => 4, 'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama' => 'Webcam', 'stok' => 4, 'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama' => 'Roll Kabel', 'stok' => 5, 'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama' => 'Lan USB Adapter', 'stok' => 4, 'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama' => 'Obeng', 'stok' => 4, 'jenis' => 'Alat Praktik','status' => 'tersedia'],
            ]];

             foreach($barang_TJKT as $TJKT =>$list){
                $jurusan = Kategori::firstOrCreate(['nama_kategori' => $TJKT]);
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
                    $tryPath = "{$TJKT}/{$var}.{$ext}";
                    
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
                    $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $brg['nama']), 0, 4));
                    $kodeUnit = sprintf('%s-%s-%04d', $TJKT, $prefix, $i);
                    Items::create([
                        'nama_item' => $brg['nama'],
                        'kode_unit' => $kodeUnit, // UNIT INDIVIDUAL
                        'jenis_item' => $brg['jenis'],
                        'kategori_jurusan_id' => $jurusan->id,
                        'status_item' => $brg['status'],
                        'foto_barang' => $encrypt   , // Semua unit punya gambar sama
                    ]);
                } 
            }
        }
    }
}
