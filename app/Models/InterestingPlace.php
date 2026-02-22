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

    // Obtiene la latitud desde el campo gps con formato "lat,lng"
    public function getLatitudeAttribute(): ?string
    {
        return $this->gpsPart(0);
    }

    // Obtiene la longitud desde el campo gps con formato "lat,lng"
    public function getLongitudeAttribute(): ?string
    {
        return $this->gpsPart(1);
    }

    // Extrae y limpia una parte del campo gps según su índice
    private function gpsPart(int $index): ?string
    {
        if (! is_string($this->gps) || $this->gps === '') {
            return null;
        }

        $parts = explode(',', $this->gps, 2);

        return isset($parts[$index]) ? trim($parts[$index]) : null;
    }
}
