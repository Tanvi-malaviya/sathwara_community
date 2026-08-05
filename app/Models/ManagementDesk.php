<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementDesk extends Model
{
    use HasFactory;

    protected $table = 'management_desk';

    protected $fillable = [
        'name',
        'name_gu',
        'designation',
        'designation_gu',
        'photo_path',
        'display_order',
        'status',
    ];

    public function getLocalizedNameAttribute()
    {
        if (app()->getLocale() === 'gu' && !empty($this->name_gu)) {
            return $this->name_gu;
        }
        return $this->name;
    }

    public function getLocalizedDesignationAttribute()
    {
        if (app()->getLocale() === 'gu' && !empty($this->designation_gu)) {
            return $this->designation_gu;
        }
        return $this->designation;
    }

    protected $casts = [
        'status' => 'boolean',
    ];
}
