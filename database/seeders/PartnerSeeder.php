<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Partner;
use App\Models\Subpartner;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Partner::factory()
            ->has(Subpartner::factory()->count(3))
            ->count(10)
            ->create();
    }
}
