<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PartnerSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            PartnerSeeder::class,
            PartnerIDSeeder::class,
        ]);
    }
}
