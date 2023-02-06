<?php

namespace Database\Factories;

use App\Models\Subpartner;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubpartnerFactory extends Factory
{
    protected $model = Subpartner::class;

    public function definition(): array
    {
    	return [
    	    'name' => $this->faker->name,
            'lotNumberId' => $this->faker->unique()->numberBetween(10,99), // sprintf("%02d", mt_rand(1,99)),
    	];
    }
}
