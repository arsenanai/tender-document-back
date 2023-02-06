<?php

namespace Tests;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\LotNumber;
use Tests\TestCase;

class LotNumberTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testModelExists()
    {
        parent::setUp();
        $object = LotNumber::find(1);
        return $this->assertTrue($object !== null);
    }

    public function testPartnerHasNeededParams()
    {
        parent::setUp();
        $object = LotNumber::find(1);
        $this->assertTrue(in_array('lotNumber', $object->getFillable()));
        $this->assertTrue(in_array('procurementNumber', $object->getFillable()));
        $this->assertTrue(in_array('partner_id', $object->getFillable()));
        $this->assertTrue(in_array('subpartner_id', $object->getFillable()));
        $this->assertTrue(in_array('orderId', $object->getFillable()));
        $this->assertTrue(in_array('comments', $object->getFillable()));
    }

    public function testPartnerHasNeededRelations()
    {
        parent::setUp();
        $object = LotNumber::find(1);
        $this->assertTrue(count($object->partner) > 0);
        $this->assertTrue(count($object->subpartner) > 0);
        $this->assertInstanceOf(Partner::class, $object->partner);
        $this->assertInstanceOf(Subpartner::class, $object->subpartner);
    }
}
