<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trek extends Model
{
    protected $fillable = [
        'regnumber',
        'name',
        'description',
        'imageUrl',
        'status',
        'municipality_id',
        'totalScore',
        'countScore'
    ];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function interestingPlaces()
    {
        return $this->belongsToMany(InterestingPlace::class, 'interesting_place_trek')->withPivot('order')->withTimestamps();
    }
}
