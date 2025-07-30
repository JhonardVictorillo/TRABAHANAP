<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformWithdrawal extends Model
{
    use HasFactory;

    
     protected $fillable = [
        'amount',
        'payment_method',
        'bank_name',
        'account_number',
        'reference_number',
        'notes',
        'admin_id',
        'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
