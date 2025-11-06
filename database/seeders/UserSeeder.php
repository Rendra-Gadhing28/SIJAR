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
<<<<<<< HEAD
          $adminJurusan = Jurusan::firstOrCreate(['nama_jurusan' => 'admin']);
          $kategoriAdmin = Kategori::firstOrCreate(['nama_kategori' => 'admin']);
       
=======
        $adminJurusan = Jurusan::firstOrCreate(['nama_jurusan' => 'admin']);
        $kategoriAdmin = Kategori::firstOrCreate(['nama_kategori' => 'admin']);
    
>>>>>>> eae2b90 (login dan homepage)
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


<<<<<<< HEAD
  

=======
>>>>>>> eae2b90 (login dan homepage)
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
