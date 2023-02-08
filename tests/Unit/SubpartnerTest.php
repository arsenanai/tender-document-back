<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\{Subpartner, PartnerID};

class SubpartnerTest extends TestCase
{
    public function testSubpartnerHasNeededRelations()
    {
        $object = Subpartner::find(1);
        $this->assertTrue(count($object->partnerIDs) > 0);
        $this->assertInstanceOf(PartnerID::class, $object->partnerIDs[0]);
        //$this->assertInstanceOf(Partner::class, $object->partner);
    }
}
