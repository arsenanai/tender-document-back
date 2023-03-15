<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\{Number, Partner, Subpartner, PartnerID, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class NumberTest extends TestCase
{
    use RefreshDatabase;
    private $admin, $partner, $subpartner, $numbers;
    public function setUp() :void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->partner = Partner::factory()->create();
        $this->numbers = Number::factory()
            ->for($this->partner)
            ->count((int)config('cnf.PAGINATION_SIZE') + 10)
            ->create();
        $this->subpartner = Subpartner::factory()
            ->for($this->partner)
            ->create();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->admin->tokens()->delete();
        $this->admin->delete();
        foreach($this->numbers as $d)
        {
            $d->delete();
        }
        $this->subpartner->delete();
        $this->partner->delete();
    }
    public function testNumberHasNeededRelations()
    {
        $object = Number::factory()
            ->for($this->partner)
            ->make();
        $this->assertIsObject($object->partner);
        $this->assertInstanceOf(Partner::class, $object->partner);
        $this->assertTrue(in_array('partner_id', $object->getFillable()));
        $this->assertTrue(in_array('lotNumber', $object->getFillable()));
        $this->assertTrue(in_array('procurementNumber', $object->getFillable()));
        //$this->assertInstanceOf(Partner::class, $object->partner);
    }

    public function testNumbersIndex()
	{
        Sanctum::actingAs( $this->admin, ['*']);
        $first = $this->numbers[(int)config('cnf.PAGINATION_SIZE') + 10-1];
        $response = $this->getJson('/api/numbers');
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json->has('data', config('cnf.PAGINATION_SIZE'))
                    ->whereContains('data.0', $first)
                    ->etc()
            );
	}

    public function testNumbersSearch()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $first = $this->numbers[(int)config('cnf.PAGINATION_SIZE') + 10-1];
        $response = $this->getJson('/api/numbers?search=' . urlencode($first->lotNumber));
        $response->assertJsonFragment(['lotNumber' => $first->lotNumber]);
        $response = $this->getJson('/api/numbers?search=' . urlencode($first->procurementNumber));
        $response->assertJsonFragment(['procurementNumber' => $first->procurementNumber]);
        $response = $this->getJson('/api/numbers?search=' . urlencode($this->partner->name));
        $response->assertJsonFragment(['name' => $this->partner->name]);
    }

    public function testNumberCanBeFilteredByPartner()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $other = Partner::factory()->create();
        $number = Number::factory()
            ->for($other)
            ->create();
        $response = $this->getJson('/api/numbers?parent=' . urlencode($other->id));
        $response->assertJson(fn (AssertableJson $json) => 
            $json->whereContains('data.0', $number)
                ->etc()
        );
    }

    public function testNumberCanBeStored()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Number::factory()->for($this->partner)->make();
        $response = $this->postJson('api/numbers', $object->toArray())
            ->assertStatus(Response::HTTP_CREATED);
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, $object[$fillable]);
        }
        Number::where('partner_id', $this->partner->id)->delete();
    }

    public function testNumberHasUniqueLotNumber()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Number::factory()->for($this->partner)->make();
        $this->postJson('api/numbers', $object->toArray())
            ->assertStatus(Response::HTTP_CREATED);
        $this->postJson('api/numbers', $object->toArray())
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'lotNumber'
                ]
            ]);
        Number::where('partner_id', $this->partner->id)->delete();
    }

    public function testNumbersProcurementNumberCanBeDuplicated()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Number::factory()->for($this->partner)->make();
        $this->postJson('api/numbers', $object->toArray())
            ->assertStatus(Response::HTTP_CREATED);
        $another = Number::factory()->for($this->partner)->make();
        $another->procurementNumber = $object->procurementNumber;
        $this->postJson('api/numbers', $another->toArray())
            ->assertStatus(Response::HTTP_CREATED);
        Number::where('partner_id', $this->partner->id)->delete();
    }

    public function testNumberIsShownCorrectly()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Number::factory()->for($this->partner)->create();
        $response = $this->getJson('api/numbers/' . $object->id)
            ->assertOk();
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
        $response->assertJsonStructure([
            'data' => [
                'partner'
            ]
        ]);
        $object->delete();
    }

    public function testNumberCanBeUpdated()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Number::factory()->for($this->partner)->create();
        $changed = Number::factory()->for($this->partner)->make();
        foreach($object->getFillable() as $fillable) {
            $object[$fillable] = $changed[$fillable];
        }
        $response = $this->putJson('api/numbers/' . $object->id, $object->toArray())
            ->assertStatus(Response::HTTP_ACCEPTED);
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
        $object->delete();
    }

    public function testNumberDelete()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Number::factory()
            ->for($this->partner)
            ->create();
        PartnerID::factory()
            ->for($object)
            ->for($this->subpartner)
            ->count(5)
            ->create();
        $children = $object->partnerIDs()->get();
        $response = $this->deleteJson('/api/numbers/' . $object->id);
        $response
            ->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseMissing('numbers', $object->toArray());
        foreach($children as $child) {
            $this->assertDatabaseMissing('partner_i_d_s', $child->toArray());
        }
        $object->delete();
    }
}
