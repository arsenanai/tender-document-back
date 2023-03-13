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
        return [
            'lotNumber' => $this->faker->unique()->randomNumber() . '',
            'procurementNumber' => $this->faker->unique()->randomNumber() . '',
        ];
    }
}
