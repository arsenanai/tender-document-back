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
    // use RefreshDatabase;
    private $admin;
    public function setUp() :void
    {
        parent::setUp();
        $this->admin = User::where('email', config('cnf.ADMIN_EMAIL'))->first();
    }

    public function tearDown(): void
    {
        parent::tearDown();
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

    public function testPartnerHasSubpartnersRelation()
    {
        $object = Partner::has('subpartners')->with('subpartners')->inRandomOrder()->first();
        $this->assertTrue(count($object->subpartners) > 0);
        $this->assertInstanceOf(Subpartner::class, $object->subpartners[0]);
    }

    public function testPartnersIndex()
	{
        Sanctum::actingAs( $this->admin, ['*']);
        $partners = Partner::where('id', '>', -1)->orderBy('id', 'desc')->paginate((int)config('cnf.PAGINATION_SIZE'));
        $first = $partners[0];
        $response = $this->getJson('/api/partners');
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json->has('data', config('cnf.PAGINATION_SIZE'))
                    ->where('data.0', $first)
                    ->etc()
            );
	}

    public function testPartnersSearch()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $partners = Partner::inRandomOrder()->paginate((int)config('cnf.PAGINATION_SIZE')+1);
        $first = $partners[(int)config('cnf.PAGINATION_SIZE')];
        $response = $this->getJson('/api/partners?search=' . urlencode($first->id));
        $response->assertJsonFragment(['id' => $first->id]);
        $response = $this->getJson('/api/partners?search=' . urlencode($first->name));
        $response->assertJsonFragment(['name' => $first->name]);
    }


    public function testPartnerCanBeStored()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Partner::factory()->make();
        $response = $this->postJson('api/partners', $object->toArray())
            ->assertStatus(Response::HTTP_CREATED);
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, $object[$fillable]);
        }
        Partner::where('name', $object->name)->delete();
    }

    public function testPartnerIsShownCorrectly()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Partner::inRandomOrder()->first();
        $response = $this->getJson('api/partners/' . $object->id)
            ->assertOk();
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
    }

    public function testPartnerCanBeUpdated()
    {
        Sanctum::actingAs( $this->admin, ['*']);
        $object = Partner::inRandomOrder()->first();
        $changed = Partner::factory()->make();
        $name = $object->name;
        $object->name = $changed->name;
        $response = $this->putJson('api/partners/' . $object->id, $object->toArray())
            ->assertStatus(Response::HTTP_ACCEPTED);
        foreach($object->getFillable() as $fillable) {
            $response->assertJsonPath('data.'.$fillable, fn ($data) => $data == $object[$fillable]);
        }
        $object->name = $name;
        $object->save();
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
