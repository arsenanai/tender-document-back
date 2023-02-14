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
        $response = $this->json('post', '/api/partner-ids/check', [
            'entry' => '211212-12-12-123',
        ]);

        $response->assertStatus(200);
    }

    public function test_partner_id_checking_is_unguarded()
    {
        $response = $this->json('post', '/api/partner-ids/check', [
            'entry' => '211212-12-12-123',
        ]);

        $response->assertStatus(200); //unathorised
    }

    public function test_partner_id_checking_entry_validation()
    {
        $response = $this->json('post', '/api/partner-ids/check', [
            'entry' => '123',
        ]);
        $response->assertStatus(422) //unprocessable entity
            ->assertJsonValidationErrorFor('entry');
    }

    public function test_partner_incorrect_id_checking_works() {
        $response = $this->json('post', '/api/partner-ids/check', [
            'entry' => '991212-12-12-123', // no entries so far
        ]);
        $response->assertStatus(200) //ok
            ->assertJsonStructure(
                [
                    'answer',
                    'reason'
                ]
            )
            ->assertJsonFragment(
                [
                    'answer' => 'incorrect'
                ]
            );
    }

    public function test_partner_correct_id_checking_works() {
        //this factory creates all other records
        $partnerID = PartnerID::inRandomOrder()->first();
        $partner = $partnerID->subpartner()->first()->partner()->first();
        $id = $partnerID->created_at->format('ymd') 
        . '-' . str_pad($partner->id, env('PAD_PARTNER_ID', 2), '0', STR_PAD_LEFT)
        . '-' . str_pad($partnerID->subpartner->id, env('PAD_SUBPARTNER_ID', 2), '0', STR_PAD_LEFT)
        . '-' . str_pad($partnerID->id, env('ID_PAD', 3), '0', STR_PAD_LEFT);
        $response = $this->json('post', '/api/partner-ids/check', [
            'entry' => $id
        ]);
        //echo 'checking id: ' . $id . PHP_EOL;
        //echo 'response: ' . json_encode($response, JSON_PRETTY_PRINT) . PHP_EOL;
        $response->assertStatus(200) //ok
            ->assertJsonStructure(
                [
                    'answer',
                    'details' => [
                        'partner',
                        'subpartner'
                    ]
                ]
            )
            ->assertJsonFragment(
                [
                    'answer' => 'correct'
                ]
            );
    }
}
