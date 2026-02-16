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
        $appDateIni = $this->resolveEnrollmentDate('appDateIni');

        return $appDateIni?->format('d-m-Y') ?? '';
    }

    public function getAppDateEndFormattedAttribute(): string
    {
        $appDateEnd = $this->resolveEnrollmentDate('appDateEnd');

        return $appDateEnd?->format('d-m-Y') ?? '';
    }

    public function getHourInputAttribute(): string
    {
        $hour = $this->getAttribute('hour');
        if (! is_string($hour) || $hour === '') {
            return '';
        }

        try {
            return Carbon::parse($hour)->format('H:i');
        } catch (\Throwable) {
            return '';
        }
    }

    public function getEnrollmentIsOpenAttribute(): bool
    {
        $appDateEnd = $this->resolveEnrollmentDate('appDateEnd');

        return $appDateEnd instanceof Carbon && Carbon::today()->lte($appDateEnd);
    }

    public static function enrollmentDatesForDay(Carbon|string $day): array
    {
        $baseDay = $day instanceof Carbon ? $day->copy() : Carbon::parse($day);

        return [
            'appDateIni' => $baseDay->copy()->subMonthNoOverflow()->toDateString(),
            'appDateEnd' => $baseDay->copy()->subWeek()->toDateString(),
        ];
    }

    private function formatDateAttribute(string $attribute): string
    {
        $value = $this->getAttribute($attribute);

        if (! $value instanceof Carbon) {
            return '';
        }

        return $value->format('d-m-Y');
    }

    private function resolveEnrollmentDate(string $attribute): ?Carbon
    {
        $day = $this->getAttribute('day');
        if ($day instanceof Carbon) {
            $enrollmentDates = self::enrollmentDatesForDay($day);

            return Carbon::parse($enrollmentDates[$attribute]);
        }

        $storedValue = $this->getAttribute($attribute);

        return $storedValue instanceof Carbon ? $storedValue : null;
    }
}
