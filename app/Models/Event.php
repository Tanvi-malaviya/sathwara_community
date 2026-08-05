<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'event_type',
        'description',
        'venue',
        'google_map_link',
        'date',
        'time',
        'published_date',
        'registration_end_date',
        'banner_path',
        'registration_option',
        'has_registration_form',
        'pass_fee',
        'max_participants',
        'status',
    ];

    protected $casts = [
        'published_date' => 'date',
        'registration_end_date' => 'date',
        'registration_option' => 'boolean',
        'has_registration_form' => 'boolean',
        'pass_fee' => 'decimal:2',
    ];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function registeredUsers()
    {
        return $this->belongsToMany(User::class, 'event_registrations', 'event_id', 'user_id');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where(function ($q) {
                         $q->whereNull('published_date')
                           ->orWhere('published_date', '<=', now()->toDateString());
                     });
    }

    public function getMapEmbedUrlAttribute()
    {
        if (empty($this->google_map_link)) {
            return null;
        }

        $link = trim($this->google_map_link);

        if (preg_match('/src=["\']([^"\']+)["\']/i', $link, $matches)) {
            return $matches[1];
        }

        if (str_contains($link, '/maps/embed') || str_contains($link, 'output=embed')) {
            return $link;
        }

        return 'https://maps.google.com/maps?q=' . urlencode($this->venue ?? $this->title) . '&output=embed';
    }
}
