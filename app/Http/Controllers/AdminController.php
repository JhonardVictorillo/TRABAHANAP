<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category; // Import the Category model
use App\Models\Post; // Import the Post model
use App\Notifications\PostApprovedNotification;

class AdminController extends Controller
{
   public function dashboard(){
        $totalFreelancers = User::where('role', 'freelancer')->count();
        $totalCustomers = User::where('role', 'customer')->count();

        $freelancers = User::with('categories')->where('role', 'freelancer')->get(); 
        $customer = User::where('role', 'customer')->get();
        $users = User::all();
      $categories = Category::all();
      $posts = Post::with('freelancer')->where('status', 'pending')->get();
  
  
        
    return View('dashboard.admin-dashboard', [
            'totalFreelancers' => $totalFreelancers,
            'totalCustomers' => $totalCustomers,
            'freelancers' => $freelancers,
            'customer' => $customer,
            'users' => $users,
           'categories' => $categories,
           'posts' => $posts,
          
        ]);
    
   }

    // Function to approve a post
    public function approvePost($id)
    {
        $post = Post::findOrFail($id);
        $post->status = 'approved';
        $post->save();

        $post->freelancer->notify(new PostApprovedNotification($post));

        return response()->json([
          'success' => true,
          'message' => 'Post approved successfully.',
          'post' => $post
      ]);
    }

    // Function to reject a post
    public function rejectPost($id)
    {
        $post = Post::findOrFail($id);
        $post->status = 'rejected';
        $post->save();

       
    return response()->json([
      'success' => true,
      'message' => 'Post rejected successfully.',
      'post' => $post
  ]);
    }

      // Function to view all posts (optional if you want a separate page)
      public function viewPosts()
      {
          $posts = Post::with('freelancer')->get(); // Fetch all posts with freelancer details
          return view('admin.posts', compact('posts'));
      }
      
       // Function to list freelancers awaiting verification
    public function listFreelancers()
    {
        $freelancer = User::where('role', 'freelancer')->where('is_verified', false)->get();
        
        return view('dashboard.admin-dashboard', compact('freelancer'));
    }

    // Function to verify a freelancer (approve them for profile visibility)
    public function verifyFreelancer($id)
    {
        $user = User::findOrFail($id);

        // Check if the user is a freelancer and not already verified
        if ($user->role === 'freelancer' && !$user->is_verified) {
            $user->is_verified = true;
            $user->save();

            // Optionally, send a notification to the freelancer that their account is verified
            // $user->notify(new FreelancerVerifiedNotification($user));

            return redirect()->route('admin.dashboard')->with('success', 'Freelancer has been verified.');
        }

        return redirect()->route('admin.dashboard')->with('error', 'This freelancer is already verified or invalid.');
    }

    // Function to reject a freelancer (deny verification)
    public function rejectFreelancer($id)
    {
        $user = User::findOrFail($id);

        // Reject the freelancer account (set verification status to false)
        if ($user->role === 'freelancer') {
            $user->is_verified = false;
            $user->save();

            return redirect()->route('admin.dashboard')->with('success', 'Freelancer account has been rejected.');
        }

        return redirect()->route('admin.dashboard')->with('error', 'This freelancer account is invalid.');
    }
}
