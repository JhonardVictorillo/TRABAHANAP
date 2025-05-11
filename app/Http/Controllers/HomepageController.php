<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

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
       
    return view('homepage', compact('posts'));
    }
}
