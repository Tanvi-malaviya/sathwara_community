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
        'form_fee',
        'max_participants',
        'status',
    ];

    protected $casts = [
        'published_date' => 'date',
        'registration_end_date' => 'date',
        'registration_option' => 'boolean',
        'has_registration_form' => 'boolean',
        'pass_fee' => 'decimal:2',
        'form_fee' => 'decimal:2',
    ];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function passes()
    {
        return $this->hasMany(EventRegistration::class)->where(function ($q) {
            $q->where('registration_type', 'pass')
              ->orWhere(function ($sub) {
                  $sub->whereNull('registration_type')->whereNotNull('pass_number');
              });
        });
    }

    public function inamSubmissions()
    {
        return $this->hasMany(EventRegistration::class)->where(function ($q) {
            $q->where('registration_type', 'inam_vitran')->orWhereNotNull('inam_number');
        });
    }

    public function yuvaMeloSubmissions()
    {
        return $this->hasMany(EventRegistration::class)->where(function ($q) {
            $q->where('registration_type', 'yuva_melo')->orWhereNotNull('yuva_melo_number');
        });
    }

    public function getLastPassNoAttribute(): int
    {
        return (int)$this->registrations()->whereNotNull('pass_number')->max('pass_number');
    }

    public function getLastInamNoAttribute(): int
    {
        return (int)$this->registrations()->whereNotNull('inam_number')->max('inam_number');
    }

    public function getLastYuvaMeloNoAttribute(): int
    {
        return (int)$this->registrations()->whereNotNull('yuva_melo_number')->max('yuva_melo_number');
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
