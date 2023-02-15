<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => env('ADMIN_EMAIL', 'admin2@entry.com'),
            'email_verified_at' => now(),
            'password' => Hash::make(env('ADMIN_INITIAL_PASSWORD', 'Entries#2023')),
        ]);
    }
}
