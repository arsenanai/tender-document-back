<?php

namespace App\Observers;

use App\Models\Subpartner;

class SubpartnerObserver
{
    public function deleting(Subpartner $subpartner)
    {
        $subpartner->partnerIDs()->delete();
    }
}
