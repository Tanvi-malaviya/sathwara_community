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
}
