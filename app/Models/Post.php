<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'freelancer_id',
        'description',
         'status',
         'rate',
         'rate_type',

    ];

    protected $casts = [
         'rate' => 'decimal:2',
    ];

   
    // Define relationships
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getFreelancerCategoryAttribute()
    {
    return $this->freelancer->categories->first();
    } 
    
   
    public function appointments()
    {
      return $this->hasMany(Appointment::class);
    }

            public function averageRating()
        {
            return $this->appointments()->whereNotNull('rating')->avg('rating') ?? 0;
        }

        public function totalReviews()
        {
            return $this->appointments()->whereNotNull('rating')->count();
        }
   
        public function pictures()
        {
            return $this->hasMany(PostPicture::class, 'post_id');
        }
    
        public function subServices()
        {
            return $this->hasMany(PostSubService::class, 'post_id');
        }

         // --- Accessors for ratings ---
    public function getAverageRatingAttribute()
    {
        return round($this->appointments()->whereNotNull('rating')->avg('rating') ?? 0, 1);
    }

    public function getReviewCountAttribute()
    {
        return $this->appointments()->whereNotNull('rating')->count();
    }
}
