<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    public function subpartners()
    {
        return $this->hasMany(Subpartner::class);
    }

    // public static function boot() {
    //     parent::boot();
    //     static::deleting(function(Partner $partner) {
    //         $partner->subpartners()->delete();
    //     });
    // }
}
