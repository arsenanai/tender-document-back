<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => config('cnf.ADMIN_NAME') . ' testing',
            'email' => config('cnf.ADMIN_EMAIL'),
            'email_verified_at' => now(),
            'password' => Hash::make(config('cnf.ADMIN_INITIAL_PASSWORD')),
        ];
    }
}
