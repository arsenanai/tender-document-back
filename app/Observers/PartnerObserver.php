<?php

namespace App\Observers;

use App\Models\Partner;

class PartnerObserver
{
    public function deleting(Partner $partner)
    {
        $partner->subpartners()->delete();
    }
}
