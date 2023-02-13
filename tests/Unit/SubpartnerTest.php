<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\{Partner, Subpartner, PartnerID, User};
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

class SubpartnerTest extends TestCase
{
    public function testSubpartnerHasNeededRelations()
    {
        $object = Subpartner::first();
        $this->assertIsObject($object->partner);
        $this->assertInstanceOf(Partner::class, $object->partner);
        //$this->assertInstanceOf(Partner::class, $object->partner);
    }

    public function testSubpartnerCanBeStored()
    {
        Sanctum::actingAs( User::where('email', env('ADMIN_EMAIL'))->first(), ['*']);
        $object = Subpartner::factory()
            ->for(Partner::inRandomOrder()->first())
            ->make();
        $response = $this->postJson('api/subpartners', $object->toArray())
            ->assertStatus(Response::HTTP_CREATED);
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, $object[$fillable]);
        }
        $object->delete();
    }

    public function testSubpartnerIsShownCorrectly()
    {
        Sanctum::actingAs( User::where('email', env('ADMIN_EMAIL'))->first(), ['*']);
        $object = Subpartner::factory()
            ->for(Partner::inRandomOrder()->first())
            ->create();
        $response = $this->getJson('api/subpartners/' . $object->id)
            ->assertOk();
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
        $object->delete();
    }
}
