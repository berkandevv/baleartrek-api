<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'comment_id'];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function getDisplayUrlAttribute(): ?string
    {
        $url = $this->url;
        if (! is_string($url) || $url === '') {
            return null;
        }

        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        return asset(ltrim($url, '/'));
    }
}
