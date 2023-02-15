<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartnerID extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'lotNumber', 'procurementNumber', 'comments', 'subpartner_id'
    ];

    public function subpartner()
    {
        return $this->belongsTo(Subpartner::class);
    }

    public function getFullEntry(): String
    {
        return $this->created_at->format('ymd') 
        . '-' . str_pad($this->subpartner->partner()->first()->id, env('PAD_PARTNER_ID', 2), '0', STR_PAD_LEFT)
        . '-' . str_pad($this->subpartner->id, env('PAD_SUBPARTNER_ID', 2), '0', STR_PAD_LEFT)
        . '-' . str_pad($this->id, env('ID_PAD', 3), '0', STR_PAD_LEFT);
    }
}
