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

        $input = trim($this->father_member_id);
        $cleanCode = str_replace(' ', '', $input);

        // 1. Try exact or space-stripped member_code match
        $user = User::with('memberProfile')
            ->where(function ($query) use ($input, $cleanCode) {
                $query->where('member_code', $input)
                    ->orWhere('member_code', $cleanCode);
            })
            ->first();

        if ($user) {
            return $user;
        }

        // 2. Try normalized SSAM + 4 digits format
        $digits = preg_replace('/[^0-9]/', '', $input);
        if (!empty($digits)) {
            $paddedCode = 'SSAM' . str_pad($digits, 4, '0', STR_PAD_LEFT);
            $user = User::with('memberProfile')->where('member_code', $paddedCode)->first();
            if ($user) {
                return $user;
            }

            // 3. Fallback: match by Database Primary Key ID if input is just numeric / #ID
            $fatherId = (int) $digits;
            if ($fatherId > 0) {
                return User::with('memberProfile')->find($fatherId);
            }
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
