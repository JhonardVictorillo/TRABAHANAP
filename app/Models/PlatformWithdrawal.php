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
        'status',
        'stripe_payout_id',
        'processed_at',
        'admin_notes'
    ];

    protected $casts = [
    'processed_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];
    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
