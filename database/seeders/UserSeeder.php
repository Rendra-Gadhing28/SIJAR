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
        $jurusanAdmin =[
            'Admin PPLG' =>[
                'nama' => 'Admin PPLG',
                'Password' => 'adM1n_PPLG',
                'kategori_id' => 1,
                'role' => 'admin',
                'kelas' => 'admin'
            ],
            'Admin TJKT' =>[
                'nama' => 'Admin TJKT',
                'Password' => 'adM1n_TJKT',
                'kategori_id' => 2,
                'role' => 'admin',
                'kelas' => 'admin'
            ],
            'Admin DKV' =>[
                'nama' => 'Admin DKV',
                'Password' => 'adM1n_DKV',
                'kategori_id' => 3,
                'role' => 'admin',
                'kelas' => 'admin'
            ],
            'Admin LK' =>[
                'nama' => 'Admin LK',
                'Password' => 'adM1n_LK',
                'kategori_id' => 4,
                'role' => 'admin',
                'kelas' => 'admin'
            ],
            'Admin PS' =>[
                'nama' => 'Admin PS',
                'Password' => 'adM1n_PS',
                'kategori_id' => 5,
                'role' => 'admin',
                'kelas' => 'admin'
            ],
        ] ;
   
        
        foreach($jurusanAdmin as $adm => $jurusan){
             $adminJurusan = Jurusan::where('nama_jurusan', $jurusan['nama'])->first();
                User::create([
                'name' => $adm,
                'email' => strtolower(str_replace(' ', '', $jurusan['nama'])).'@gmail.com',
                'password' => bcrypt($jurusan['Password']),
                'role' => $jurusan['role'],
                'jurusan_id' => $adminJurusan->id,
                'kategori_id' => $adminJurusan->kategori_id,
                'kelas' => $jurusan['kelas']
            ]);}


            $kelas = [
            'X',
            'XI',
            'XII',]; 

       $jurusans = Jurusan::where('nama_jurusan', 'not like', '%Admin%')->get();
        foreach ($kelas as $kls) {
            foreach ($jurusans as $jrs) {
            $name = $kls.' '.$jrs->nama_jurusan;
                User::create([
                    'name' => $name,//X PPLG 3
                    'email' => strtoupper(str_replace(' ', '', $name)) . '@gmail.com',//XPPLG3@gmail.com
                    //sijar_xpplg3
                    'password' => bcrypt( str_replace(' ', '', strtolower($name))),
                    'role' => 'user',
                    'jurusan_id' => $jrs->id,
                    'kategori_id' => $jrs->kategori_id,
                    'kelas' => $kls,
                ]);
            }
        }
    }
} 
