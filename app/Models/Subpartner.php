<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subpartner extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'lotNumberId',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
