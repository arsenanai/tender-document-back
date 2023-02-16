<?php

namespace Tests\Unit;

use App\Models\Partner;
use App\Models\PartnerID;
use App\Models\Subpartner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PartnerIDTest extends TestCase
{
    use RefreshDatabase;
    private $admin, $partner, $subpartner;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->partner = Partner::factory()->create();
        $this->subpartner = Subpartner::factory()->for($this->partner)->create();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->admin->tokens()->delete();
        $this->admin->delete();
        $this->partner->delete();
        // subpartner will be deleted by cascade
    }

    public function testModelExists()
    {
        $object = PartnerID::factory()
            ->for($this->subpartner)
            ->make();
        return $this->assertTrue($object !== null);
    }

    public function testPartnerIDHasNeededParams()
    {
        $object = PartnerID::factory()
            ->for($this->subpartner)
            ->make();
        $this->assertTrue(in_array('lotNumber', $object->getFillable()));
        $this->assertTrue(in_array('procurementNumber', $object->getFillable()));
        $this->assertTrue(in_array('comments', $object->getFillable()));
    }

    public function testPartnerIDHasNeededRelations()
    {
        $object = PartnerID::factory()
            ->for($this->subpartner)
            ->make();
        $this->assertIsObject($object->subpartner);
        $this->assertInstanceOf(Subpartner::class, $object->subpartner);
    }

    public function testPartnerIDCanBeStored()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = PartnerID::factory()
            ->for($this->subpartner)
            ->make();
        $response = $this->postJson('api/partner-ids', $object->toArray())
            ->assertStatus(Response::HTTP_CREATED);
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
        PartnerID::where('comments', 'like', '%testing')->delete();
    }

    public function testPartnerIDIsShownCorrectly()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = PartnerID::factory()
            ->for($this->subpartner)
            ->create();
        $response = $this->getJson('api/partner-ids/' . $object->id)
            ->assertOk();
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
        $response->assertJsonPath('data.fullEntry', $object->getFullEntry());
        $object->delete();
    }

    public function testPartnerIDCanBeUpdated()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = PartnerID::factory()
            ->for($this->subpartner)
            ->create();
        $changed = PartnerID::factory()
            ->for($this->subpartner)
            ->make();
        foreach($object->getFillable() as $fillable) {
            $object[$fillable] = $changed[$fillable];
        }
        $response = $this->putJson('api/partner-ids/' . $object->id, $object->toArray())
            ->assertStatus(Response::HTTP_ACCEPTED);
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
        $response->assertJsonPath('data.fullEntry', $object->getFullEntry());
        $object->delete();
    }

    public function testPartnerIDsIndex()
	{
        Sanctum::actingAs( $this->admin, ['*']);
        $ids = PartnerID::factory()
            ->for($this->subpartner)
            ->count((int)config('cnf.PAGINATION_SIZE') + 10)
            ->create();
        $first = $ids[0];
        $response = $this->getJson('/api/partner-ids');
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json->has('data', config('cnf.PAGINATION_SIZE'))
                    ->where('data.0', $first)
                    ->etc()
            );
        foreach ($ids as $id)
        {
            $id->delete();
        }
	}

    public function testPartnerIDDelete()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = PartnerID::factory()->for($this->subpartner)->create();
        $response = $this->deleteJson('/api/partner-ids/' . $object->id);
        $response
            ->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseMissing('partner_i_d_s', $object->toArray());
    }
}
