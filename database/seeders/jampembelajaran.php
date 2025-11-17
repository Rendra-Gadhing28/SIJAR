<?php

namespace Database\Seeders;

use App\Models\waktu_pembelajaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class jampembelajaran extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jampembelajaran = [
            1 => [
                'start' => '07:00:00',
                'end' => '07:45:00'
            ],
            2 => [
                'start' => '07:45:00',
                'end' => '08:30:00'
            ],
            3 => [
                'start' => '08:30:00',
                'end' => '09:15:00'
            ],
            4 => [
                'start' => '09:30:00',
                'end' => '10:15:00'
            ],
            5 => [
                'start' => '10:15:00',
                'end' => '11:00:00'
            ],
            6 => [
                'start' => '11:00:00',
                'end' => '11:45:00'
            ],
            7 => [
                'start' => '12:30:00',
                'end' => '13:15:00'
            ],
            8 => [
                'start' => '13:15:00',
                'end' => '14:00:00'
            ],
            9 => [
                'start' => '14:00:00',
                'end' => '14:45:00'
            ],
            10 => [
                'start' => '14:45:00',
                'end' => '15:30:00'
            ],
        ];
        foreach($jampembelajaran as $jam => $data){
                waktu_pembelajaran::create([
                    'jam_ke' => $jam,
                    'start_time' => $data['start'],
                    'end_time' => $data['end'],
        ]);
        }

    }
}
