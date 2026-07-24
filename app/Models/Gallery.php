<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'image_path',
        'caption',
        'display_order',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
