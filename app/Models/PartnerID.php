<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartnerID extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'comments', 'subpartner_id'
    ];

    public function subpartner()
    {
        return $this->belongsTo(Subpartner::class);
    }

    public function getFullEntry(): String
    {
        return $this->created_at->format('ymd') 
        . '-' . str_pad($this->subpartner->partner()->first()->id, config('cnf.PAD_PARTNER_ID'), '0', STR_PAD_LEFT)
        . '-' . str_pad($this->subpartner->id, config('cnf.PAD_SUBPARTNER_ID'), '0', STR_PAD_LEFT)
        . '-' . str_pad($this->id, config('cnf.ID_PAD'), '0', STR_PAD_LEFT);
    }
}
