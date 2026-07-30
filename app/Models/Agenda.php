<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_gu',
        'description',
        'description_gu',
        'icon',
        'display_order',
    ];

    public function getLocalizedTitleAttribute()
    {
        if (app()->getLocale() === 'gu' && !empty($this->title_gu)) {
            return $this->title_gu;
        }
        return $this->title;
    }

    public function getLocalizedDescriptionAttribute()
    {
        if (app()->getLocale() === 'gu' && !empty($this->description_gu)) {
            return $this->description_gu;
        }
        return $this->description;
    }
}
