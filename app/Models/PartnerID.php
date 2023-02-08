<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartnerID extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'lotNumber', 'procurementNumber', 'threeDigitId', 'comments',
    ];

    public function subpartner()
    {
        return $this->belongsTo(Subpartner::class);
    }
}
