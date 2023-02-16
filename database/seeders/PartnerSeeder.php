<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Partner, Subpartner, PartnerID};

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
            ->has(
                Subpartner::factory()
                ->has(PartnerID::factory()->count(5))
                ->count(3)
                )
            ->count((int)config('cnf.PAGINATION_SIZE', 20) + 5)
            ->create();
    }
}
