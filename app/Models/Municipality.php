<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $fillable = [
        'name', 
        'zone_id',
        'island_id'
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function island()
    {
        return $this->belongsTo(Island::class);
    }

    public function treks()
    {
        return $this->hasMany(Trek::class);
    }
}
