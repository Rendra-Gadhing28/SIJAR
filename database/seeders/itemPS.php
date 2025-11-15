<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Item;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
class itemPS extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barang_PS = [  'PS' => [
                ['nama'=> 'Camera Canon', 'stok' => 1,'jenis' => 'Elektronik','status' => 'rusak'],
                ['nama'=> 'LCD', 'stok'=> 1,'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama'=> 'Pengeras Suara', 'stok'=> 1,'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama'=> 'Remote LCD', 'stok'=> 1,'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama'=> 'Mouse', 'stok'=> 22,'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama'=> 'Recording Sony', 'stok'=> 12,'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama'=> 'Deckstand', 'stok'=> 8,'jenis' => 'Alat Praktik','status' => 'tersedia'],
                ['nama'=> 'Digital Voice Recorder', 'stok'=> 14,'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama'=> 'Sony Powerfull Sound', 'stok'=> 11,'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama'=> 'Camera Nikon', 'stok'=> 1,'jenis' => 'Elektronik','status' => 'rusak'],
                ['nama'=> 'Conference Microfon merk TUM', 'stok'=> 3,'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama'=> 'Desk Standing Phillips Microphone', 'stok'=> 10,'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama'=> 'Headphone Sennsheiser', 'stok'=> 5,'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama'=> 'Pic Trainer Converencde', 'stok'=> 2,'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama'=> 'Kertas Braile', 'stok'=> 1,'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama'=> 'Kaca Mata Terapi', 'stok'=> 2,'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama'=> 'Stand Mic berdiri', 'stok'=> 2 ,'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama'=> 'Batre', 'stok'=> 2,'jenis' => 'Elektronik','status' => 'tersedia'],
                ['nama'=> 'Camera Sony', 'stok'=> 1,'jenis' => 'Elektronik','status' => 'rusak'],
            ]];

             foreach($barang_PS as $PS =>$list){
                $jurusan = Kategori::firstOrCreate(['nama_kategori' => $PS]);
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
                    $tryPath = "{$PS}/{$var}.{$ext}";
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
                    $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $brg['nama']), 0, 15));
                    $kodeUnit = sprintf('%s-%s-%04d', $PS, $prefix, $i);
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

