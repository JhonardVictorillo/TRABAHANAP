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
         'location_restriction',
          'service_duration',
        'buffer_time',
        'scheduling_mode'

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



    public function getDefaultDuration()
{
    // If post has explicit duration, use it
    if (!empty($this->service_duration)) {
        return $this->service_duration;
    }
    
    // Get category name from the freelancer's category
    $categoryName = null;
    if ($this->freelancer && $this->freelancer->categories()->first()) {
        $categoryName = strtolower($this->freelancer->categories()->first()->name);
    }
    
    // Map service categories to default durations (in minutes)
    $durationMap = [
        'welder' => 180,
        'event designer' => 120,
        'housekeeping' => 180,
        'grooming' => 60,
        'electricals' => 120,
        'gardening' => 120,
        'technicians' => 90,
        'masonry' => 240,
        'plumbing' => 120,
        'tutoring' => 60,
        'carpentry' => 180,
        'nails services' => 60,
    ];
    
    // Look for exact category match or partial match
    if (isset($durationMap[$categoryName])) {
        return $durationMap[$categoryName];
    }
    
    // Check for partial matches
    foreach ($durationMap as $category => $duration) {
        if (strpos($categoryName, $category) !== false) {
            return $duration;
        }
    }
    
    return 60; // Default to 1 hour
}

public function getBufferTime()
{
    return $this->buffer_time ?: 15;
}
}
