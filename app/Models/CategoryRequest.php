<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category_name',
        'status',
        'admin_notes',
        'processed_by',
        'processed_at'
    ];
    
    protected $casts = [
        'processed_at' => 'datetime',
    ];
    
    /**
     * Get the user that owns the category request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the admin who processed the request.
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}

