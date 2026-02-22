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

    // Devuelve el día en formato dd-mm-YYYY para mostrar en UI
    public function getDayFormattedAttribute(): string
    {
        return $this->formatDateAttribute('day');
    }

    // Expone la fecha de apertura de inscripción en formato legible
    public function getAppDateIniFormattedAttribute(): string
    {
        $appDateIni = $this->resolveEnrollmentDate('appDateIni');

        return $appDateIni?->format('d-m-Y') ?? '';
    }

    // Expone la fecha de cierre de inscripción en formato legible
    public function getAppDateEndFormattedAttribute(): string
    {
        $appDateEnd = $this->resolveEnrollmentDate('appDateEnd');

        return $appDateEnd?->format('d-m-Y') ?? '';
    }

    // Convierte el valor de hora al formato HH:mm usado por inputs time
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

    // Indica si la inscripción sigue abierta según appDateEnd y la fecha actual
    public function getEnrollmentIsOpenAttribute(): bool
    {
        $appDateEnd = $this->resolveEnrollmentDate('appDateEnd');

        return $appDateEnd instanceof Carbon && Carbon::today()->lte($appDateEnd);
    }

    // Calcula fechas de inscripción a partir del día de la salida
    public static function enrollmentDatesForDay(Carbon|string $day): array
    {
        $baseDay = $day instanceof Carbon ? $day->copy() : Carbon::parse($day);

        return [
            'appDateIni' => $baseDay->copy()->subMonthNoOverflow()->toDateString(),
            'appDateEnd' => $baseDay->copy()->subWeek()->toDateString(),
        ];
    }

    // Formatea un atributo de fecha si está correctamente casteado a Carbon
    private function formatDateAttribute(string $attribute): string
    {
        $value = $this->getAttribute($attribute);

        if (! $value instanceof Carbon) {
            return '';
        }

        return $value->format('d-m-Y');
    }

    // Prioriza el cálculo por day y si no existe usa el valor guardado en base de datos
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
