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
        'post_id',
        'rating',
        'decline_reason',
        'commitment_fee',
        'fee_status',
        'cancelled_by',
        'cancelled_at',
        'status',
        'stripe_session_id',
         'total_amount',
        'final_payment_status',
        'final_stripe_session_id',
        'completed_at',
        'review',
        'no_show_by',
        'duration',
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
    
    public function post()
    {
         return $this->belongsTo(Post::class, 'post_id');
     }

    public function categories()
    {
        return $this->freelancer->categories();
     }

    
    // Helper methods for commitment fee
    public function forfeitCommitmentFee()
    {
        $this->commitment_fee_status = 'forfeited';
        $this->save();
    }

    public function refundCommitmentFee()
    {
        $this->commitment_fee_status = 'refunded';
        $this->save();
    }
}



