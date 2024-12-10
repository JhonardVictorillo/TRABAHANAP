<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'freelancer_id',
        'sub_services',
        'description',
         'post_picture',
    ];

    protected $casts = [
        'sub_services' => 'array', // If it's stored as a JSON or array type in the database
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

   
}
