<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    use HasFactory;
    protected $fillable = [
        'partner_id', 'lotNumber', 'procurementNumber',
    ];
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function partnerIDs()
    {
        return $this->hasMany(PartnerID::class);
    }
}
