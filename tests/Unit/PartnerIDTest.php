<?php

namespace Tests\Unit;

use App\Models\Number;
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
    private $admin, $partner, $subpartner, $number;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->partner = Partner::factory()->create();
        $this->subpartner = Subpartner::factory()->for($this->partner)->create();
        $this->number = Number::factory()->for($this->partner)->create();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->admin->tokens()->delete();
        $this->admin->delete();
        $this->partner->delete();
        // others will be deleted by cascade
    }

    public function testModelExists()
    {
        $object = PartnerID::factory()
            ->for($this->subpartner)
            ->for($this->number)
            ->make();
        return $this->assertTrue($object !== null);
    }

    public function testPartnerIDHasNeededParams()
    {
        $object = PartnerID::factory()
            ->for($this->subpartner)
            ->for($this->number)
            ->make();
        $this->assertTrue(in_array('subpartner_id', $object->getFillable()));
        $this->assertTrue(in_array('number_id', $object->getFillable()));
        $this->assertTrue(in_array('comments', $object->getFillable()));
    }

    public function testPartnerIDHasNeededRelations()
    {
        $object = PartnerID::factory()
            ->for($this->subpartner)
            ->for($this->number)
            ->make();
        $this->assertIsObject($object->subpartner);
        $this->assertIsObject($object->number);
        $this->assertInstanceOf(Subpartner::class, $object->subpartner);
        $this->assertInstanceOf(Number::class, $object->number);
    }

    public function testPartnerIDCanBeStored()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = PartnerID::factory()
            ->for($this->subpartner)
            ->for($this->number)
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
            ->for($this->number)
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
            ->for($this->number)
            ->create();
        $changed = PartnerID::factory()
            ->for($this->subpartner)
            ->for($this->number)
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
            ->for($this->number)
            ->count((int)config('cnf.PAGINATION_SIZE') + 10)
            ->create();
        $first = $ids[(int)config('cnf.PAGINATION_SIZE') + 10-1];
        $response = $this->getJson('/api/partner-ids');
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json->has('data', config('cnf.PAGINATION_SIZE'))
                    ->whereContains('data.0', $first)
                    ->etc()
            );
        foreach ($ids as $id)
        {
            $id->delete();
        }
	}

    public function testPartnerIDsSearch()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $ids = PartnerID::factory()
            ->for($this->subpartner)
            ->for($this->number)
            ->count((int)config('cnf.PAGINATION_SIZE') + 10)
            ->create();
        $first = $ids[(int)config('cnf.PAGINATION_SIZE') + 5];
        $response = $this->getJson('/api/partner-ids?search=' . urlencode($this->number->lotNumber));
        $response->assertJsonFragment(['lotNumber' => $this->number->lotNumber]);
        $response = $this->getJson('/api/partner-ids?search=' . urlencode($this->number->procurementNumber));
        $response->assertJsonFragment(['procurementNumber' => $this->number->procurementNumber]);
        $response = $this->getJson('/api/partner-ids?search=' . urlencode($this->partner->name));
        $response->assertJsonFragment(['name' => $this->partner->name]);
        $response = $this->getJson('/api/partner-ids?search=' . urlencode($this->subpartner->name));
        $response->assertJsonFragment(['name' => $this->subpartner->name]);
        $response = $this->getJson('/api/partner-ids?search=' . urlencode($this->getFullEntry($first)));
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json->whereContains('data.0', $first)
                    ->etc()
            );
        foreach ($ids as $id)
        {
            $id->delete();
        } 
    }

    private function getFullEntry($obj) {
        return $obj->created_at->format('ymd') 
        . '-' . str_pad($this->partner->id, config('cnf.PAD_PARTNER_ID'), '0', STR_PAD_LEFT)
        . '-' . str_pad($this->subpartner->id, config('cnf.PAD_SUBPARTNER_ID'), '0', STR_PAD_LEFT)
        . '-' . str_pad($obj->id, config('cnf.ID_PAD'), '0', STR_PAD_LEFT);
    }

    public function testPartnerIDDelete()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = PartnerID::factory()->for($this->subpartner)->for($this->number)->create();
        $response = $this->deleteJson('/api/partner-ids/' . $object->id);
        $response
            ->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseMissing('partner_i_d_s', $object->toArray());
    }
}
