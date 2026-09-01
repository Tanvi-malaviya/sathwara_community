<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessPaymentLink extends Model
{
    protected $fillable = [
        'business_id',
        'amount',
        'razorpay_link_id',
        'razorpay_link_url',
        'status',
        'razorpay_payment_id',
        'created_by',
        'paid_at',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isExpired(): bool
    {
        return $this->status === 'created' && $this->expires_at->isPast();
    }
}
