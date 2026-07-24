<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pincode',
    ];

    public function memberProfiles()
    {
        return $this->hasMany(MemberProfile::class);
    }
}
