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
use App\Models\Number;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Testing\Fluent\AssertableJson;

class PartnerIDCheckTest extends TestCase
{
    use RefreshDatabase;
    public function setUp() :void
    {
        parent::setUp();
        $this->seed();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
    
    public function test_partner_ids_route_exists()
    {
        $response = $this->json('post', '/api/partner-ids/check', [
            'entry' => '211212-12-12-123',
        ]);

        $response->assertStatus(200);
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
        $response->assertStatus(200); //ok
        $r = [
            'answer',
            'reason'
        ];
        if (config('cnf.APP_DEBUG') == 'true') {
            array_push($r, 'details');
        }
        $response->assertJsonStructure($r)
            ->assertJsonFragment(
                [
                    'answer' => 'incorrect'
                ]
            );
    }

    public function test_partner_correct_id_checking_works() {
        $partner = Partner::has('subpartners.partnerIDs')->inRandomOrder()->first();
        $subpartner = Subpartner::has('partnerIDs')->where('partner_id', $partner->id)->inRandomOrder()->first();
        //$number = Number::where('partner_id', $partner->id)->inRandomOrder()->first();
        // echo json_encode($partner, JSON_PRETTY_PRINT) . PHP_EOL;
        // echo json_encode($subpartner, JSON_PRETTY_PRINT) . PHP_EOL;
        $partnerID = PartnerID::where('subpartner_id', $subpartner->id)->with('subpartner')
        ->inRandomOrder()->first();
        $this->assertTrue(
            method_exists(PartnerID::class, 'getFullEntry'), 
            'PartnerID does not have method getFullEntry'
        );
        $response = $this->json('post', '/api/partner-ids/check', [
            'entry' => $partnerID->getFullEntry()
        ]);
        // echo json_encode($response, JSON_PRETTY_PRINT);
        $response->assertStatus(200) //ok
            ->assertJsonStructure(
                [
                    'answer',
                    'details' => [
                        //'partner',    
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
