<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'area_id',
        'member_id',
        'business_name',
        'owner_name',
        'description',
        'address',
        'phone',
        'whatsapp',
        'email',
        'website',
        'logo_path',
        'gallery_images',
        'status',
        'membership_status',
        'facebook',
        'instagram',
        'youtube',
        'linkedin',
        'approved_at',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'approved_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::retrieved(function ($business) {
            if ($business->status === 'approved' && $business->membership_status === 'active' && $business->approved_at && $business->approved_at->addYear()->isPast()) {
                $business->membership_status = 'inactive';
                $business->saveQuietly();
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
                     ->where('membership_status', 'active');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(BusinessCategory::class, 'category_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
