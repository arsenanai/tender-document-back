<?php

namespace Tests\Unit;
use App\Models\Partner;
use App\Models\Subpartner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BasePartnerTest extends TestCase
{
    use RefreshDatabase;
    private $admin;
    public function setUp() :void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->admin->tokens()->delete();
        $this->admin->delete();
    }
    public function testPartnerExists()
    {
        $object = Partner::factory()->make();
        $this->assertTrue($object !== null);
    }

    public function testPartnerHasTwoDigitIdParam()
    {
        $object = Partner::factory()->make();
        $this->assertTrue(in_array('name', $object->getFillable()));
    }

    public function testPartnerHasPartnerRelation()
    {
        $object = Partner::factory()
            ->has(
                Subpartner::factory()->count(3)
                )
            ->create();
        $this->assertTrue(count($object->subpartners) > 0);
        $this->assertInstanceOf(Subpartner::class, $object->subpartners[0]);
        $object->delete();
    }

    public function testPartnersIndex()
	{
        Sanctum::actingAs( $this->admin, ['*']);
        $partners = Partner::factory()
            ->count((int)env('PAGINATION_SIZE', 20) + 10)
            ->create();
        $first = $partners[0];
        $response = $this->getJson('/api/partners');
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json->has('data', env('PAGINATION_SIZE', 20))
                    ->where('data.0', $first)
                    ->etc()
            );
        foreach($partners as $partner)
        {
            $partner->delete();
        }
	}

    public function testPartnerCanBeStored()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Partner::factory()
            ->make();
        $response = $this->postJson('api/partners', $object->toArray())
            ->assertStatus(Response::HTTP_CREATED);
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, $object[$fillable]);
        }
        Partner::where('name', 'like', '%testing')->delete();
    }

    public function testPartnerIsShownCorrectly()
    {
        Sanctum::actingAs( $this->admin, ['*']);
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
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Partner::factory()->create();
        $changed = Partner::factory()->make();
        foreach($object->getFillable() as $fillable) {
            $object[$fillable] = $changed[$fillable];
        }
        $response = $this->putJson('api/partners/' . $object->id, $object->toArray())
            ->assertStatus(Response::HTTP_ACCEPTED);
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
        $object->delete();
    }

    public function testPartnerDelete()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Partner::factory()
            ->has(Subpartner::factory()->count(3))
            ->create();
        $children = $object->subpartners()->get();
        $response = $this->deleteJson('/api/partners/' . $object->id);
        $response
            ->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseMissing('partners', $object->toArray());
        foreach($children as $child) {
            $this->assertDatabaseMissing('subpartners', $child->toArray());
        }
    }
}
