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

    public function isVideo(): bool
    {
        if (empty($this->image_path)) {
            return false;
        }
        $path = strtolower($this->image_path);
        $videoExtensions = ['mp4', 'mov', 'webm', 'ogg', 'm4v', 'avi', 'mkv', '3gp'];
        $cleanPath = parse_url($path, PHP_URL_PATH) ?? $path;
        $ext = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));
        if (in_array($ext, $videoExtensions)) {
            return true;
        }
        return false;
    }

    public function getUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return '';
        }
        return str_starts_with($this->image_path, 'http') ? $this->image_path : asset('storage/' . $this->image_path);
    }
}
