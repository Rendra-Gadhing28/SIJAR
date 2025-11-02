<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        User::create([
            'name' => 'Admin Jurusan',
            'email' => 'adminjurusan@gmail.com',
            'password' => bcrypt('adM1n_jUurus4nn'),
            'role' => 'admin'
        ]);

        $kelas = [
            'X PS 1',
            'X PS 2',
            'X TJKT 1',
            'X TJKT 2',
            'X PPLG 1',
            'X PPLG 2',
            'X PPLG 3',
            'X DKV 1',
            'X DKV 2',
            'X DKV 3',
            'X LK 1',
            'X LK 2',
            'XI PS 1',
            'XI PS 2',
            'XI TJKT 1',
            'XI TJKT 2',
            'XI PPLG 1',
            'XI PPLG 2',
            'XI PPLG 3',
            'XI DKV 1',
            'XI DKV 2',
            'XI DKV 3',
            'XI LK 1',
            'XI LK 2',
            'XII PS 1',
            'XII PS 2',
            'XII TJKT 1',
            'XII TJKT 2',
            'XII PPLG 1',
            'XII PPLG 2',
            'XII PPLG 3',
            'XII DKV 1',
            'XII DKV 2',
            'XII DKV 3',
            'XII LK 1', 
            'XII LK 2',
        ];

        foreach ($kelas as $k) {
            User::create([
                'name' => $k,
                'email' => strtolower(str_replace(' ', '', $k)) . '@gmail.com',
                'password' => bcrypt('sijar_' . str_replace(' ', '', strtolower($k))),
                'role' => 'user',
            ]);
        }

        User::Factory()->count(10)->create();
    }
} 
