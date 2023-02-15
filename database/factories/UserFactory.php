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
            'name' => env('ADMIN_NAME', 'Admin') . ' testing',
            'email' => 'admin@entry.com',
            'email_verified_at' => now(),
            'password' => Hash::make(env('ADMIN_INITIAL_PASSWORD', 'Entry_2023')),
        ];
    }
}
