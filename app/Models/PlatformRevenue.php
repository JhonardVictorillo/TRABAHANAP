<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformRevenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'source',
        'appointment_id',
        'user_id',
        'date',
        'notes'
    ];


     public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the user associated with this revenue
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
