<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Carbon|null $day
 * @property Carbon|null $appDateIni
 * @property Carbon|null $appDateEnd
 */
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

    protected $casts = [
        'day' => 'date',
        'appDateIni' => 'date',
        'appDateEnd' => 'date',
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
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getDayFormattedAttribute(): string
    {
        return $this->formatDateAttribute('day');
    }

    public function getAppDateIniFormattedAttribute(): string
    {
        return $this->formatDateAttribute('appDateIni');
    }

    public function getAppDateEndFormattedAttribute(): string
    {
        return $this->formatDateAttribute('appDateEnd');
    }

    public function getEnrollmentIsOpenAttribute(): bool
    {
        $appDateEnd = $this->getAttribute('appDateEnd');

        if (! $appDateEnd instanceof Carbon) {
            return false;
        }

        return Carbon::today()->lte($appDateEnd);
    }

    private function formatDateAttribute(string $attribute): string
    {
        $value = $this->getAttribute($attribute);

        if (! $value instanceof Carbon) {
            return '';
        }

        return $value->format('d-m-Y');
    }
}
