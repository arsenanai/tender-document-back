<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'name' => env('ADMIN_NAME'),
            'email' => env('ADMIN_EMAIL'),
            'email_verified_at' => now(),
            'password' => Hash::make(env('ADMIN_INITIAL_PASSWORD')),
        ];
    }
}
