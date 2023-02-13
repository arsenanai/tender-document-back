<?php

namespace Tests\Feature;

use App\Http\Controllers\PartnerIDController;
use App\Models\Partner;
use App\Models\PartnerID;
use App\Models\Subpartner;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Resources\PartnerIDResource;
use Illuminate\Http\Request;
use Illuminate\Testing\Fluent\AssertableJson;

class PartnerIDCheckTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_partner_ids_route_exists()
    {
        $admin = User::where('email', env('ADMIN_EMAIL'))->first();
        Sanctum::actingAs(
            $admin,
            ['*']
        );
        $response = $this->json('post', '/api/partner-ids/check', [
            'entry' => '211212-12-12-123',
        ]);

        $response->assertStatus(200);
    }

    public function test_partner_id_checking_is_guarded()
    {
        $response = $this->json('post', '/api/partner-ids/check', [
            'entry' => '211212-12-12-123',
        ]);

        $response->assertStatus(401); //unathorised
    }

    public function test_partner_id_checking_entry_validation()
    {
        $admin = User::where('email', env('ADMIN_EMAIL'))->first();
        Sanctum::actingAs(
            $admin,
            ['*']
        );
        $response = $this->json('post', '/api/partner-ids/check', [
            'entry' => '123',
        ]);
        $response->assertStatus(422) //unprocessable entity
            ->assertJsonValidationErrorFor('entry');
    }

    public function test_partner_incorrect_id_checking_works() {
        $admin = User::where('email', env('ADMIN_EMAIL'))->first();
        Sanctum::actingAs(
            $admin,
            ['*']
        );
        $response = $this->json('post', '/api/partner-ids/check', [
            'entry' => '991212-12-12-123', // no entries so far
        ]);
        $response->assertStatus(200) //ok
            ->assertJsonFragment(
                [
                    'answer' => 'incorrect', 
                    'reason' => 'mismatch'
                ]
            );
    }

    public function test_partner_correct_id_checking_works() {
        $admin = User::where('email', env('ADMIN_EMAIL'))->first();
        Sanctum::actingAs(
            $admin,
            ['*']
        );
        //this factory creates all other records
        $partnerID = PartnerID::inRandomOrder()->first();
        $partner = Subpartner::find($partnerID->subpartner->id)->partner;
        $response = $this->json('post', '/api/partner-ids/check', [
            'entry' => $partnerID->created_at->format('yymmdd') 
            . '-' . str_pad($partner->id, env('PAD_PARTNER_ID', 2), '0', STR_PAD_LEFT)
            . '-' . str_pad($partnerID->subpartner->id, env('PAD_SUBPARTNER_ID', 2), '0', STR_PAD_LEFT)
            . '-' . str_pad($partnerID->id, env('ID_PAD', 3), '0', STR_PAD_LEFT)
        ]);
        // var_dump($response); exit;
        $response->assertStatus(200) //ok
            ->assertJsonStructure(
                [
                    'answer',
                    'partner',
                    'subpartner'
                ]
            )
            ->assertJsonFragment(
                [
                    'answer' => 'correct'
                ]
            );
    }

    // CRUD checking

    /** @test */
	public function test_partner_ids_index()
	{
        Sanctum::actingAs( User::where('email', env('ADMIN_EMAIL'))->first(), ['*']);
        $first = PartnerID::first();
        $response = $this->getJson('/api/partner-ids');
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json->has('data', env('PAGINATION_SIZE', 20))
                    ->where('data.0', $first)
                    ->etc()
            );
	}
    
    // public function test_partner_id_create()
    // {
    //     Sanctum::actingAs( User::where('email', env('ADMIN_EMAIL'))->first(), ['*']);
        
    // }
}
