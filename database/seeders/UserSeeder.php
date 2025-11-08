<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Jurusan;
use  App\Models\Kategori;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        $adminJurusan = Jurusan::firstOrCreate(['nama_jurusan' => 'admin']);
        $kategoriAdmin = Kategori::firstOrCreate(['nama_kategori' => 'admin']);
    
        $kelas = [
            'X',
            'XI',
            'XII',
        ]; 

        $admin = [
            'admin'
        ];

        foreach ($admin as $adm) {
            User::create([
                'name' => 'Admin Jurusan',
                'email' => 'adminjurusan@gmail.com',
                'password' => bcrypt('adM1n_jUurus4nn'),
                'role' => 'admin',
                'jurusan_id' => $adminJurusan->where('nama_jurusan', 'admin')->first()->id,
                'kategori_id' => $kategoriAdmin->id,
                'kelas' => $admin[0]
            ]);
        }


        $jurusans = Jurusan::where('nama_jurusan', '!=', 'admin')->get();
        foreach ($kelas as $kls) {
            foreach ($jurusans as $jrs) {
            $name = $kls.' '.$jrs->nama_jurusan;
                User::create([
                    'name' => $name,
                    'email' => strtolower(str_replace(' ', '', $name)) . '@gmail.com',
                    'password' => bcrypt('sijar_' . str_replace(' ', '', strtolower($name))),
                    'role' => 'user',
                    'jurusan_id' => $jrs->id,
                    'kategori_id' => $jrs->kategori_id,
                    'kelas' => $kls,
                ]);
            }
        }
    }
} 
