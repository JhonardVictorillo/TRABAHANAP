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
            
        ])
        ->where('status', 'approved')
        ->whereHas('freelancer', function($q){
            $q->where('is_verified', true);
        })
        ->latest()
        ->take(10)
        ->get();

         $categories =Category::all();
           $ipGeoApiKey = env('IPGEOLOCATION_API_KEY');

    return view('homepage', compact('posts', 'categories', 'ipGeoApiKey'));
    }
}
