<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\{Partner, Subpartner, PartnerID};

class SubpartnerTest extends TestCase
{
    public function testSubpartnerHasNeededRelations()
    {
        $object = Subpartner::first();
        $this->assertIsObject($object->partner);
        $this->assertInstanceOf(Partner::class, $object->partner);
        //$this->assertInstanceOf(Partner::class, $object->partner);
    }
}
