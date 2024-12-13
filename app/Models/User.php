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
         'profile_completed',
         'profile_picture'
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
     
}
