<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\{Number, Partner, Subpartner, PartnerID, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class SubpartnerTest extends TestCase
{
    use RefreshDatabase;
    private $admin, $partner, $subpartners, $number;
    public function setUp() :void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->partner = Partner::factory()->create();
        $this->subpartners = Subpartner::factory()
            ->for($this->partner)
            ->count((int)config('cnf.PAGINATION_SIZE') + 10)
            ->create();
        $this->number = Number::factory()
            ->for($this->partner)
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
        $this->number->delete();
        $this->partner->delete();
    }
    public function testSubpartnerHasNeededRelations()
    {
        $object = Subpartner::factory()
            ->for($this->partner)
            ->make();
        $this->assertIsObject($object->partner);
        $this->assertInstanceOf(Partner::class, $object->partner);
        $this->assertTrue(in_array('partner_id', $object->getFillable()));
        $this->assertTrue(in_array('name', $object->getFillable()));
        //$this->assertInstanceOf(Partner::class, $object->partner);
    }

    public function testSubpartnersIndex()
	{
        Sanctum::actingAs( $this->admin, ['*']);
        $first = $this->subpartners[(int)config('cnf.PAGINATION_SIZE') + 10-1];
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
        $first = $this->subpartners[(int)config('cnf.PAGINATION_SIZE') + 10-1];
        $response = $this->getJson('/api/subpartners?search=' . urlencode($first->id));
        $response->assertJson(fn (AssertableJson $json) => 
            $json->whereContains('data.0', $first)
                ->etc()
        );
        $response = $this->getJson('/api/subpartners?search=' . urlencode($first->name));
        $response->assertJson(fn (AssertableJson $json) => 
            $json->whereContains('data.0', $first)
                ->etc()
        );
        $response = $this->getJson('/api/subpartners?search=' . urlencode($this->partner->name));
        $response->assertJson(fn (AssertableJson $json) => 
            $json->whereContains('data.0', $first)
                ->etc()
        );
    }

    public function testSubpartnerCanBeFilteredByPartner()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $other = Partner::factory()->create();
        $subpartner = Subpartner::factory()
            ->for($other)
            ->create();
        $response = $this->getJson('/api/subpartners?parent=' . urlencode($other->id));
        $response->assertJson(fn (AssertableJson $json) => 
            $json->whereContains('data.0', $subpartner)
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
            ->create();
        PartnerID::factory()
            ->for($object)
            ->for($this->number)
            ->count(5)
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
