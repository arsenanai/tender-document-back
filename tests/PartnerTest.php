<?php

namespace Tests;

//use Laravel\Lumen\Testing\DatabaseMigrations;
//use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Partner;
use App\Models\Subpartner;
use Tests\TestCase;

class PartnerTest extends TestCase
{
    public function testPartnerExists()
    {
        parent::setUp();
        $object = Partner::find(1);
        $this->assertTrue($object !== null);
    }

    public function testPartnerHasLotIdParam()
    {
        parent::setUp();
        $object = Partner::find(1);
        $this->assertTrue(in_array('lotNumberId', $object->getFillable()));
    }

    public function testPartnerHasSubpartnerRelation()
    {
        parent::setUp();
        $object = Partner::find(1);
        $this->assertTrue(count($object->subpartners) > 0);
        $this->assertInstanceOf(Subpartner::class, $object->subpartners[0]);
    }
}
