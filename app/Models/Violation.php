<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id',
        'user_role',
        'violation_type',
        'appointment_id',
        'notes',
        'status',
        'resolved_at',
        'resolved_by'
    ];
    
    protected $casts = [
        'resolved_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    
    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
    
    public function actions()
    {
        return $this->hasMany(ViolationAction::class);
    }
}
