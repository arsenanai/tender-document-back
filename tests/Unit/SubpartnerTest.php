<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\{Partner, Subpartner, PartnerID, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class SubpartnerTest extends TestCase
{
    use RefreshDatabase;
    private $admin, $partner, $subpartners;
    public function setUp() :void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->partner = Partner::factory()->create();
        $this->subpartners = Subpartner::factory()
            ->for($this->partner)
            ->count((int)config('cnf.PAGINATION_SIZE') + 10)
            ->create();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->admin->tokens()->delete();
        $this->admin->delete();
        foreach($this->subpartners as $d)
        {
            $d->delete();
        }
        $this->partner->delete();
    }
    public function testSubpartnerHasNeededRelations()
    {
        $object = Subpartner::factory()
            ->for($this->partner)
            ->make();
        $this->assertIsObject($object->partner);
        $this->assertInstanceOf(Partner::class, $object->partner);
        //$this->assertInstanceOf(Partner::class, $object->partner);
    }

    public function testSubpartnersIndex()
	{
        Sanctum::actingAs( $this->admin, ['*']);
        $first = $this->subpartners[0];
        $response = $this->getJson('/api/subpartners');
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json->has('data', config('cnf.PAGINATION_SIZE'))
                    ->whereContains('data.0', $first)
                    ->etc()
            );
	}

    public function testSubpartnersSearch()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $first = $this->subpartners[0];
        $response = $this->getJson('/api/subpartners?search=' . $first->id);
        $response->assertJson(fn (AssertableJson $json) => 
            $json->whereContains('data.0', $first)
                ->etc()
        );
        $response = $this->getJson('/api/subpartners?search=' . $first->name);
        $response->assertJson(fn (AssertableJson $json) => 
            $json->whereContains('data.0', $first)
                ->etc()
        );
        $response = $this->getJson('/api/subpartners?search=' . $this->partner->name);
        $response->assertJson(fn (AssertableJson $json) => 
            $json->whereContains('data.0', $first)
                ->etc()
        );
    }

    public function testSubpartnerCanBeStored()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Subpartner::factory()->for($this->partner)->make();
        $response = $this->postJson('api/subpartners', $object->toArray())
            ->assertStatus(Response::HTTP_CREATED);
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, $object[$fillable]);
        }
        Subpartner::where('name', 'like', '%testing')->delete();
    }

    public function testSubpartnerIsShownCorrectly()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Subpartner::factory()->for($this->partner)->create();
        $response = $this->getJson('api/subpartners/' . $object->id)
            ->assertOk();
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
        $object->delete();
    }

    public function testSubpartnerCanBeUpdated()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Subpartner::factory()->for($this->partner)->create();
        $changed = Subpartner::factory()->for($this->partner)->make();
        foreach($object->getFillable() as $fillable) {
            $object[$fillable] = $changed[$fillable];
        }
        $response = $this->putJson('api/subpartners/' . $object->id, $object->toArray())
            ->assertStatus(Response::HTTP_ACCEPTED);
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
        $object->delete();
    }

    public function testSubpartnerDelete()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Subpartner::factory()
            ->for($this->partner)
            ->has(PartnerID::factory()->count(5))
            ->create();
        $children = $object->partnerIDs()->get();
        $response = $this->deleteJson('/api/subpartners/' . $object->id);
        $response
            ->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseMissing('subpartners', $object->toArray());
        foreach($children as $child) {
            $this->assertDatabaseMissing('partner_i_d_s', $child->toArray());
        }
        $object->delete();
    }
}
