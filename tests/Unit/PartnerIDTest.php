<?php

namespace Tests\Unit;

use App\Models\Partner;
use App\Models\PartnerID;
use App\Models\Subpartner;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PartnerIDTest extends TestCase
{

    public function testModelExists()
    {
        $object = PartnerID::first();
        return $this->assertTrue($object !== null);
    }

    public function testPartnerHasNeededParams()
    {
        $object = PartnerID::first();
        $this->assertTrue(in_array('lotNumber', $object->getFillable()));
        $this->assertTrue(in_array('procurementNumber', $object->getFillable()));
        $this->assertTrue(in_array('comments', $object->getFillable()));
    }

    public function testPartnerHasNeededRelations()
    {
        $object = PartnerID::first();
        $this->assertIsObject($object->subpartner);
        $this->assertInstanceOf(Subpartner::class, $object->subpartner);
    }

    public function testPartnerIDCanBeStored()
    {
        Sanctum::actingAs( User::where('email', env('ADMIN_EMAIL'))->first(), ['*']);
        $object = PartnerID::factory()
            ->for(Subpartner::inRandomOrder()->first())
            ->make();
        $response = $this->postJson('api/partner-ids', $object->toArray())
            ->assertStatus(Response::HTTP_CREATED);
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
        $object->delete();
    }

    public function testPartnerIDIsShownCorrectly()
    {
        Sanctum::actingAs( User::where('email', env('ADMIN_EMAIL'))->first(), ['*']);
        $object = PartnerID::factory()
            ->for(Subpartner::inRandomOrder()->first())
            ->create();
        $response = $this->getJson('api/partner-ids/' . $object->id)
            ->assertOk();
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
        $object->delete();
    }
}
