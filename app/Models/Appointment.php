<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'freelancer_id',
        'customer_id',
        'date',
        'time',
        'name',
        'address',
        'contact',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Relationship to the freelancer.
     */
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }
}



