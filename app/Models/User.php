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
         'experience_level',
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
        'hourly_rate',
        'daily_rate',
        'current_mode',
        'role_updated_at',
        'freelancer_onboarded',
        'customer_onboarded',
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

    // Add helper methods to work with your existing role column
public function isFreelancer()
{
    // Assuming your role values are 'freelancer', 'customer', or 'both'
    return $this->role === 'freelancer' || $this->role === 'both';
}

public function isCustomer()
{
    return $this->role === 'customer' || $this->role === 'both';
}

public function isInFreelancerMode()
{
    return $this->current_mode === 'freelancer';
}

public function isInCustomerMode()
{
    return $this->current_mode === 'customer';
}

public function switchToFreelancerMode()
{
    if (!$this->isFreelancer()) {
        return false;
    }
    
    $this->current_mode = 'freelancer';
    $this->save();
    return true;
}

public function switchToCustomerMode()
{
    if (!$this->isCustomer()) {
        return false;
    }
    
    $this->current_mode = 'customer';
    $this->save();
    return true;
}

public function becomeFreelancer()
{
    if ($this->role === 'customer') {
        $this->role = 'both';
        $this->role_updated_at = now();
        $this->save();
    } else if ($this->role === null) {
        $this->role = 'freelancer';
        $this->role_updated_at = now();
        $this->save();
    }
}

public function becomeCustomer()
{
    if ($this->role === 'freelancer') {
        $this->role = 'both';
        $this->role_updated_at = now();
        $this->save();
    } else if ($this->role === null) {
        $this->role = 'customer';
        $this->role_updated_at = now();
        $this->save();
    }
}
}
