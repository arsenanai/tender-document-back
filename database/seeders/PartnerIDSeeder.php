<?php

namespace Database\Seeders;

use App\Models\Number;
use App\Models\PartnerID;
use App\Models\Subpartner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartnerIDSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 25; $i++) {
            $subpartner = Subpartner::inRandomOrder()->first();
            PartnerID::factory()
                ->for($subpartner)
                ->for(
                    Number::where('partner_id', $subpartner->partner_id)
                        ->inRandomOrder()->first()
                )
                ->count(3)
                ->create();
        }
    }
}
