<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'freelancer_id',
        'appointment_id',
        'amount',
        'source',
        'date',
        'notes'
    ];


    // Relationship to freelancer
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    // Relationship to appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
