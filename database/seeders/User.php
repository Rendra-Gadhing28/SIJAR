<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class User extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::Factory()->count(10)->create();
    }
}
