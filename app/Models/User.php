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
        'password',
        'contact_number',
        'role',
         'province', 
         'city', 
         'zipcode', 
         'google_map_link', 
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
}
