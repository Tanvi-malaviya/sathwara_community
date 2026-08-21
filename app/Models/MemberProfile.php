<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'father_member_id',
        'gender',
        'dob',
        'blood_group',
        'education',
        'occupation',
        'phone',
        'whatsapp',
        'address',
        'area_id',
        'city',
        'state',
        'pincode',
        'photo_path',
        'aadhaar_number',
        'aadhaar_path',
        'pan_number',
        'pan_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function getFatherUserAttribute()
    {
        if (!$this->father_member_id) {
            return null;
        }
        $fatherId = (int) preg_replace('/[^0-9]/', '', $this->father_member_id);
        if ($fatherId > 0) {
            return User::with('memberProfile')->find($fatherId);
        }
        return null;
    }

    public function getFullNameAttribute(): string
    {
        return trim(preg_replace('/\s+/', ' ', implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ]))));
    }
}
