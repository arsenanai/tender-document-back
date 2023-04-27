<?php

namespace Database\Factories;

use App\Models\Number;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class NumberFactory extends Factory
{
    protected $model = Number::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        do {
            $lotNumber = $this->faker->unique()->numberBetween(11111111, 99999999);
        } while(Number::where('lotNumber', $lotNumber)->count() > 0);
        return [
            'lotNumber' => $lotNumber . '',
            'procurementNumber' => $this->faker->unique()->numberBetween(11111111, 99999999) 
            . $this->faker->name(),
        ];
    }
}
