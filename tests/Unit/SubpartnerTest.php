<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\{Partner, Subpartner, PartnerID, User};
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
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

    public function testSubpartnersIndex()
	{
        Sanctum::actingAs( User::where('email', env('ADMIN_EMAIL'))->first(), ['*']);
        $first = Subpartner::first();
        $response = $this->getJson('/api/subpartners');
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json->has('data', env('PAGINATION_SIZE', 20))
                    ->where('data.0', $first)
                    ->etc()
            );
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

    public function testSubpartnerCanBeUpdated()
    {
        Sanctum::actingAs( User::where('email', env('ADMIN_EMAIL'))->first(), ['*']);
        $object = Subpartner::inRandomOrder()->first();
        $changed = Subpartner::factory()
            ->for(Partner::inRandomOrder()->first())
            ->make();
        foreach($object->getFillable() as $fillable) {
            $object[$fillable] = $changed[$fillable];
        }
        $response = $this->putJson('api/subpartners/' . $object->id, $object->toArray())
            ->assertStatus(Response::HTTP_ACCEPTED);
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
    }

    public function testSubpartnerDelete()
    {
        Sanctum::actingAs( User::where('email', env('ADMIN_EMAIL'))->first(), ['*']);
        $object = Subpartner::inRandomOrder()->first();
        $children = $object->partnerIDs()->get();
        $response = $this->deleteJson('/api/subpartners/' . $object->id);
        $response
            ->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseMissing('subpartners', $object->toArray());
        foreach($children as $child) {
            $this->assertDatabaseMissing('partner_i_d_s', $child->toArray());
        }
        // $object->save();
        // $object->partnerIDs()->createMany($children->toArray());
    }
}
