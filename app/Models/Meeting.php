<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'trek_id',
        'user_id',
        'appDateIni',
        'appDateEnd',
        'day',
        'hour',
        'totalScore',
        'countScore'
    ];

    public function trek()
    {
        return $this->belongsTo(Trek::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'meeting_user')->withTimestamps();
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
