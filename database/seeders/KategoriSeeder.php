<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'PPLG', 'role' => 'user', 'icon' => 'icon/pplg.png'],
            ['nama_kategori' => 'LK',   'role' => 'user', 'icon' => 'icon/lk1.png'],
            ['nama_kategori' => 'TJKT', 'role' => 'user', 'icon' => 'icon/tjkt.png'],
            ['nama_kategori' => 'DKV',  'role' => 'user', 'icon' => 'icon/dkv.png'],
            ['nama_kategori' => 'PS',   'role' => 'user', 'icon' => 'icon/ps.png'],
        ];

        foreach ($kategori as $data) {
            $encryptedName = null;

            $tryPath = $data['icon']; // ← path asli tiap kategori

            if (Storage::disk('public')->exists($tryPath)) {
                // ✅ Ambil ekstensi dari path asli
                $extension     = pathinfo($tryPath, PATHINFO_EXTENSION); // png / jpg / dll
                $content       = Storage::disk('public')->get($tryPath);

                // ✅ Hash isi file + time + nama supaya unik
                $encryptedName = hash('sha256', $content . time() . $tryPath) . '.' . $extension;
                $gambarPath    = 'icons/' . $encryptedName;

                // ✅ Copy file ke folder encrypted
                Storage::disk('public')->copy($tryPath, $gambarPath);
            }

            Kategori::firstOrCreate(
                ['nama_kategori' => $data['nama_kategori']], // ← cek by ini
                [
                    'icon'       => $encryptedName, // null kalau gambar tidak ada
                    'role'       => $data['role'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}