<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'dni',
        'email',
        'phone',
        'password',
        'role_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->password;
    }

    public function getRouteKeyName(): string
    {
        return 'email';
    }

    public function isAdmin(): bool
{
    return $this->role?->name === 'admin';
}

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function meetings()
    {
        return $this->belongsToMany(Meeting::class, 'meeting_user')->withTimestamps();
    }

    public function meeting()
    {
        return $this->hasOne(Meeting::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
