<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViolationSetting extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_role',
        'no_show_penalties',
        'auto_warning',
        'rating_penalty',
        'booking_restrictions',
        'auto_suspension',
        'suspension_days'
    ];
    
    protected $casts = [
        'no_show_penalties' => 'boolean',
        'auto_warning' => 'boolean',
        'rating_penalty' => 'boolean',
        'booking_restrictions' => 'boolean',
        'auto_suspension' => 'boolean',
    ];
}
