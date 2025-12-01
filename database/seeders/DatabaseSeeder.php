<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the user seeder to populate users
        $this->call([
            KategoriSeeder::class,
            JurusanSeeder::class,
            UserSeeder::class,
            itemDKV::class,
            itemLK::class,
            itemPPLG::class,
            itemPS::class,
            itemTJKT::class,
            jampembelajaran::class
        ]);
    }
}
