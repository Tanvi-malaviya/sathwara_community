<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponsorshipType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'amount',
        'max_sponsors',
        'description',
        'status',
        'display_order',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'max_sponsors' => 'integer',
        'status' => 'boolean',
        'display_order' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function sponsors()
    {
        return $this->hasMany(EventSponsor::class);
    }

    public function approvedSponsors()
    {
        return $this->hasMany(EventSponsor::class)->where('status', 'approved');
    }

    /**
     * Get remaining sponsor slots (null if unlimited)
     */
    public function getAvailableSlotsAttribute(): ?int
    {
        if (empty($this->max_sponsors) || $this->max_sponsors <= 0) {
            return null; // Unlimited
        }

        $approvedCount = $this->relationLoaded('approvedSponsors') 
            ? $this->approvedSponsors->count() 
            : $this->approvedSponsors()->count();

        return max(0, $this->max_sponsors - $approvedCount);
    }

    /**
     * Check if slots are fully booked
     */
    public function getIsFullAttribute(): bool
    {
        if (empty($this->max_sponsors) || $this->max_sponsors <= 0) {
            return false;
        }

        $approvedCount = $this->relationLoaded('approvedSponsors') 
            ? $this->approvedSponsors->count() 
            : $this->approvedSponsors()->count();

        return $approvedCount >= $this->max_sponsors;
    }
}
