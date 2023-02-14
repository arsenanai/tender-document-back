<?php

namespace Tests\Unit;
use App\Models\Partner;
use App\Models\Subpartner;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PartnerTest extends TestCase
{
    public function testPartnerExists()
    {
        $object = Partner::first();
        $this->assertTrue($object !== null);
    }

    public function testPartnerHasTwoDigitIdParam()
    {
        $object = Partner::first();
        $this->assertTrue(in_array('name', $object->getFillable()));
    }

    public function testPartnerHasPartnerRelation()
    {
        $object = Partner::first();
        $this->assertTrue(count($object->subpartners) > 0);
        $this->assertInstanceOf(Subpartner::class, $object->subpartners[0]);
    }

    public function testPartnersIndex()
	{
        Sanctum::actingAs( User::where('email', env('ADMIN_EMAIL'))->first(), ['*']);
        $first = Partner::first();
        $response = $this->getJson('/api/partners');
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json->has('data', env('PAGINATION_SIZE', 20))
                    ->where('data.0', $first)
                    ->etc()
            );
	}

    public function testPartnerCanBeStored()
    {
        Sanctum::actingAs( User::where('email', env('ADMIN_EMAIL'))->first(), ['*']);
        $object = Partner::factory()
            ->make();
        $response = $this->postJson('api/partners', $object->toArray())
            ->assertStatus(Response::HTTP_CREATED);
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, $object[$fillable]);
        }
        $object->delete();
    }

    public function testPartnerIsShownCorrectly()
    {
        Sanctum::actingAs( User::where('email', env('ADMIN_EMAIL'))->first(), ['*']);
        $object = Partner::factory()
            ->create();
        $response = $this->getJson('api/partners/' . $object->id)
            ->assertOk();
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
        $object->delete();
    }

    public function testPartnerCanBeUpdated()
    {
        Sanctum::actingAs( User::where('email', env('ADMIN_EMAIL'))->first(), ['*']);
        $object = Partner::inRandomOrder()->first();
        $changed = Partner::factory()->make();
        foreach($object->getFillable() as $fillable) {
            $object[$fillable] = $changed[$fillable];
        }
        $response = $this->putJson('api/partners/' . $object->id, $object->toArray())
            ->assertStatus(Response::HTTP_ACCEPTED);
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
    }

    public function testPartnerDelete()
    {
        Sanctum::actingAs( User::where('email', env('ADMIN_EMAIL'))->first(), ['*']);
        $object = Partner::inRandomOrder()->first();
        $children = $object->subpartners()->get();
        $response = $this->deleteJson('/api/partners/' . $object->id);
        $response
            ->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseMissing('partners', $object->toArray());
        foreach($children as $child) {
            $this->assertDatabaseMissing('subpartners', $child->toArray());
        }
        // $object->save();
        // $object->subpartners()->createMany($children->toArray());
    }
}
