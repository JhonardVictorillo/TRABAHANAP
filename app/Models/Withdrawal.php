<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'status',
        'stripe_payout_id',
        'payment_details',
        'notes',
        'admin_notes',
        'processed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     * Get the freelancer that owns the withdrawal.
     */
    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the admin who processed the withdrawal.
     */
    public function processedBy(): BelongsTo
    {
        // Assuming you track who processed the withdrawal
        // If not, you can remove this method
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope a query to only include withdrawals with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include pending withdrawals.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include processing withdrawals.
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    /**
     * Scope a query to only include completed withdrawals.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include rejected withdrawals.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Check if withdrawal is in pending state.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if withdrawal is in processing state.
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if withdrawal is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if withdrawal is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Process the withdrawal request.
     */
    public function markAsProcessing(?string $adminNotes = null): bool
    {
        $this->status = 'processing';
        if ($adminNotes) {
            $this->admin_notes = $adminNotes;
        }
        return $this->save();
    }

    /**
     * Complete the withdrawal.
     */
    public function markAsCompleted(?string $stripePayoutId = null, ?string $adminNotes = null): bool
    {
        $this->status = 'completed';
        $this->processed_at = now();
        
        if ($stripePayoutId) {
            $this->stripe_payout_id = $stripePayoutId;
        }
        
        if ($adminNotes) {
            $this->admin_notes = $adminNotes;
        }
        
        return $this->save();
    }

    /**
     * Reject the withdrawal.
     */
    public function markAsRejected(string $reason): bool
    {
        $this->status = 'rejected';
        $this->admin_notes = $reason;
        $this->processed_at = now();
        return $this->save();
    }
}