<?php

namespace Tests\Unit;
use App\Models\Partner;
use App\Models\Subpartner;
use App\Models\User;
use Illuminate\Http\Response;
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

    public function testPartnerHasSubpartnerRelation()
    {
        $object = Partner::first();
        $this->assertTrue(count($object->subpartners) > 0);
        $this->assertInstanceOf(Subpartner::class, $object->subpartners[0]);
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
}
