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
        'designation',
        'message',
        'photo_path',
        'display_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
