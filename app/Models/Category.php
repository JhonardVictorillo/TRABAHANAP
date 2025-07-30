<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_path'
    ];

   public function users(): BelongsToMany
{
    return $this->belongsToMany(User::class, 'category_user', 'category_id', 'user_id');
}

// Helper method to get user count
    public function getUserCountAttribute()
    {
        return $this->users()->count();
    }
    
    // Helper method to get image URL
    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return asset('images/default-category.jpg');
        }
        
        return asset('storage/' . $this->image_path);
    }
}
