<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViolationAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'violation_id',
        'action_type',
        'notes',
        'action_data',
        'admin_id'
    ];
    
    protected $casts = [
        'action_data' => 'array',
    ];
    
    public function violation()
    {
        return $this->belongsTo(Violation::class);
    }
    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
