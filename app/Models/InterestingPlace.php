<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterestingPlace extends Model
{
    protected $fillable = [
        'gps',
        'name',
        'place_type_id'
    ];

    public function placeType()
    {
        return $this->belongsTo(PlaceType::class);
    }

    public function treks()
    {
        return $this->belongsToMany(Trek::class, 'interesting_place_trek')->withPivot('order')->withTimestamps();
    }
}
