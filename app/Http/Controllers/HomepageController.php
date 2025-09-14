<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class HomepageController extends Controller
{
    public function index()
    {
        $posts = Post::with([
            'freelancer.categories',
            'subServices',
            'pictures',
            'freelancer',
            'appointments'
        ])
        ->where('status', 'approved')
        ->whereHas('freelancer', function($q){
            $q->where('is_verified', true);
        })
        ->latest()
        ->take(10)
        ->get()  // This was missing a closing parenthesis
      ->map(function ($post) {
            // Get only appointments with ratings
            $ratedAppointments = $post->appointments->whereNotNull('rating');
            $reviewCount = $ratedAppointments->count();
            $averageRating = $reviewCount > 0 
                ? $ratedAppointments->avg('rating') 
                : 0;
                
            // Add these as attributes to the post
            $post->average_rating = $averageRating;
            $post->review_count = $reviewCount;
            
            return $post;
        });

        // Sort posts by rating for "Top-Rated Professionals" section
        $topRatedPosts = $posts->sortByDesc('average_rating');

         $categories =Category::all();
           $ipGeoApiKey = env('IPGEOLOCATION_API_KEY');

    return view('homepage', compact('posts', 'categories', 'ipGeoApiKey'));
    }
}
