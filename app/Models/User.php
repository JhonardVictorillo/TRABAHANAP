<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        
        'firstname', // Added firstname
        'lastname',  // Added lastname
        'email',
        'bio', // Added bio
        'password',
        'contact_number',
        'role',
         'province', 
         'city', 
         'zipcode', 
         'barangay', 
         'id_front', 
         'id_back', 
         'experience_years',
         'profile_completed',
         'profile_picture',
         'email_verification_token',
         'email_verified_at',
         'no_show_count',
        'late_cancel_count',
        'violation_count',
        'last_violation_at',
        'specialization',
        'skills',
        'is_verified',
        'current_mode',
        'role_updated_at',
        'freelancer_onboarded',
        'customer_onboarded',
         'is_suspended',
        'suspended_until',
        'is_banned',
         'is_restricted',
        'restriction_end',
        'restriction_reason',
        'ban_reason',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
             'suspended_until' => 'datetime',
            'last_violation_at' => 'datetime',
            'is_suspended' => 'boolean',
            'is_banned' => 'boolean',
            'is_restricted' => 'boolean',
             'restriction_end' => 'datetime',
            
        ];
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_user', 'user_id', 'category_id');
    }

//     public function freelancerCategory()
// {
//     return $this->belongsTo(Category::class); // Adjust to your model and database
// }

public function posts()
{
    return $this->hasMany(Post::class, 'freelancer_id');
}

public function appointments()
{
    return $this->hasMany(Appointment::class, 'freelancer_id');
}
public function reviews()
{
    return $this->hasMany(Appointment::class, 'freelancer_id');
}

public function freelancer()
{
    return $this->belongsTo(User::class, 'freelancer_id');
}
   
public function availabilities()
{
    return $this->hasMany(FreelancerAvailability::class, 'freelancer_id');
}

public function categoryRequests()
{
    return $this->hasMany(CategoryRequest::class);
}


    // Helper methods for violations
    public function incrementNoShow()
    {
        $this->increment('no_show_count');
        $this->increment('violation_count');
        $this->last_violation_at = now();
        $this->save();
    }

    public function incrementLateCancel()
    {
        $this->increment('late_cancel_count');
        $this->increment('violation_count');
        $this->last_violation_at = now();
        $this->save();
    }

    public function getIsProfileCompleteAttribute()
{
    return !empty($this->bio) && 
           !empty($this->province) && 
           !empty($this->city) && 
           !empty($this->zipcode) && 
           !empty($this->profile_picture) && 
           !empty($this->valid_id_front) && 
           !empty($this->valid_id_back) && 
           $this->categories()->exists();
}

 public function jobSuccessRate()
{
    // Make sure you have an appointments relationship
    $totalJobs = $this->appointments()->count();
    $successfulJobs = $this->appointments()->where('status', 'completed')->count();
    return $totalJobs > 0 ? round(($successfulJobs / $totalJobs) * 100) : 0;
}


public function isSuspended(): bool
{
    if (!$this->is_suspended) {
        return false;
    }
    
    // If suspended but the suspension period has ended
    if ($this->suspended_until && now()->greaterThan($this->suspended_until)) {
        $this->is_suspended = false;
        $this->suspended_until = null;
        $this->save();
        return false;
    }
    
    return true;
}

/**
 * Suspend user for specified number of days
 */
public function suspend(int $days = 7, string $reason = null): void
{
    $this->is_suspended = true;
    $this->suspended_until = now()->addDays($days);
    $this->save();
}

public function hasRestrictions(): bool
{
    return $this->is_restricted && 
           (!$this->restriction_end || now()->lessThan($this->restriction_end));
}

/**
 * Apply restrictions to user account
 */
public function applyRestrictions(int $days = 7): void
{
    $this->is_restricted = true;
    $this->restriction_end = now()->addDays($days);
    $this->restriction_reason = 'Multiple violations';
    $this->save();
}

/**
 * Remove restrictions from user account
 */
public function removeRestrictions(): void
{
    $this->is_restricted = false;
    $this->restriction_end = null;
    $this->restriction_reason = null;
    $this->save();
}

/**
 * Ban user permanently
 */
public function ban(string $reason = null): void
{
    $this->is_banned = true;
    $this->ban_reason = $reason ?? 'Repeated violations';
    $this->save();
}

/**
 * Check if user can create posts
 */
public function canCreatePosts(): bool
{
    return !$this->isSuspended() && !$this->hasRestrictions() && !$this->is_banned;
}

/**
 * Check if user can book appointments
 */
public function canBookAppointments(): bool
{
    return !$this->isSuspended() && !$this->is_banned;
}
}
