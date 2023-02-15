<?php

namespace Database\Factories;

use App\Models\PartnerID;
use App\Models\Subpartner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PartnerID>
 */
class PartnerIDFactory extends Factory
{
    protected $model = PartnerID::class;

    public function definition(): array
    {
    	return [
            'lotNumber' => $this->faker->randomNumber() . '',
            'procurementNumber' => $this->faker->randomNumber() . '',
            'comments' => $this->faker->text(42) . ' testing',
    	];
    }
}
