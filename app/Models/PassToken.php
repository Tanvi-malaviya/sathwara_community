<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassToken extends Model
{
    use HasFactory;

    protected $table = 'event_pass_tokens';

    protected $fillable = [
        'event_registration_id',
        'event_id',
        'pass_index',
        'pass_code',
        'token_hash',
        'is_checked_in',
        'checked_in_at',
        'checked_in_by',
    ];

    protected $casts = [
        'pass_index' => 'integer',
        'is_checked_in' => 'boolean',
        'checked_in_at' => 'datetime',
    ];

    public function registration()
    {
        return $this->belongsTo(EventRegistration::class, 'event_registration_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function checkedInUser()
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }
}
