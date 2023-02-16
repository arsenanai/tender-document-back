<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAdminLoginsSuccessfully()
    {
        $admin = User::factory()->create();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('post', '/api/login', [
            'email' => 'admin@entry.com',
            'password' => config('cnf.ADMIN_INITIAL_PASSWORD')
        ]);
 
        $response->assertOk();
        $response->assertJsonStructure(
            [
                'success',
                'message',
                'data' => [
                  'token',
                  'user'
                ]
            ]
        );
        $admin->tokens()->delete();
        $admin->delete();
    }

    public function testAdminLogoutSuccessfully()
    {
        $admin = User::factory()->create();
        Sanctum::actingAs( $admin, ['*']);
        $this->postJson('/api/logout')
            ->assertOk()
            ->assertJsonStructure(
                [
                    'success',
                    'message'
                ]
                );
        $admin->tokens()->delete();
        $admin->delete();
    }
}
