<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FreelancerAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'freelancer_id',
        'date', 
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    /**
     * Accessor for Start Time
     */
    public function getStartTimeAttribute($value)
    {
        if (!$value) {
            \Log::warning('Start Time is null or empty.', ['value' => $value]);
            return null; // Return null if the value is empty
        }

        try {
            // Parse the time from the database format (H:i:s) and return in 12-hour format (g:i A)
            return Carbon::createFromFormat('H:i:s', $value)->format('g:i A');
        } catch (\Exception $e) {
            \Log::error('Failed to parse Start Time.', [
                'value' => $value,
                'error' => $e->getMessage(),
            ]);
            return $value; // Return the raw value if parsing fails
        }
    }

    /**
     * Accessor for End Time
     */
    public function getEndTimeAttribute($value)
    {
        if (!$value) {
            \Log::warning('End Time is null or empty.', ['value' => $value]);
            return null; // Return null if the value is empty
        }

        try {
            // Parse the time from the database format (H:i:s) and return in 12-hour format (g:i A)
            return Carbon::createFromFormat('H:i:s', $value)->format('g:i A');
        } catch (\Exception $e) {
            \Log::error('Failed to parse End Time.', [
                'value' => $value,
                'error' => $e->getMessage(),
            ]);
            return $value; // Return the raw value if parsing fails
        }
    }

    /**
     * Raw Start Time for Input Fields
     */
    public function getRawStartTimeAttribute()
    {
        return $this->attributes['start_time']; // Return the raw database value (H:i:s)
    }

    /**
     * Raw End Time for Input Fields
     */
    public function getRawEndTimeAttribute()
    {
        return $this->attributes['end_time']; // Return the raw database value (H:i:s)
    }
}