<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptSequence extends Model
{
    protected $fillable = [
        'financial_year',
        'last_number',
    ];
}
