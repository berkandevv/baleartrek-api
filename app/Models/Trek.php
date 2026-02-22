<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    // Normaliza la imagen para devolver una URL utilizable en frontend
    public function getImageDisplayUrlAttribute(): ?string
    {
        $imageUrl = $this->imageUrl;
        if (! is_string($imageUrl) || $imageUrl === '') {
            return null;
        }

        if (Str::startsWith($imageUrl, ['http://', 'https://'])) {
            return $imageUrl;
        }

        return asset(ltrim($imageUrl, '/'));
    }
}
