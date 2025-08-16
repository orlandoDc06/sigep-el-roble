<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $fillable = [
        'name',
        'description',
        'default_amount',
        'applies_to_all',
        'is_percentage',
    ];
}
