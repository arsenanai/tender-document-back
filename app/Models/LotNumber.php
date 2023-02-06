<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LotNumber extends Model
{
    use HasFactory;
    protected $fillable = [
        'lotNumber', 'procurementNumber', 'partner_id', 'subpartner_id', 'orderId', 'comments',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function subpartner()
    {
        return $this->belongsTo(Subpartner::class);
    }
}
