<?php

namespace Tests\Unit;
use App\Models\Partner;
use App\Models\Subpartner;
use Tests\TestCase;

class PartnerTest extends TestCase
{
    public function testPartnerExists()
    {
        $object = Partner::find(1);
        $this->assertTrue($object !== null);
    }

    public function testPartnerHasTwoDigitIdParam()
    {
        $object = Partner::find(1);
        $this->assertTrue(in_array('twoDigitId', $object->getFillable()));
    }

    public function testPartnerHasSubpartnerRelation()
    {
        $object = Partner::find(1);
        $this->assertTrue(count($object->subpartners) > 0);
        $this->assertInstanceOf(Subpartner::class, $object->subpartners[0]);
    }
}
