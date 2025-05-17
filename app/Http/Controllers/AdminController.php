<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category; // Import the Category model
use App\Models\Post; // Import the Post model
use App\Models\Appointment;
use App\Models\PlatformRevenue;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\PostApprovedNotification;

class AdminController extends Controller
{
   public function dashboard(Request $request){
        $totalFreelancers = User::where('role', 'freelancer')->count();
        $totalCustomers = User::where('role', 'customer')->count();
     
        // Fetch pending accounts (freelancers with is_verified = 0)
        $totalPendingAccounts = User::where('role', 'freelancer')->where('is_verified', 0)->count();

        // Fetch pending posts (posts with status = 'pending')
        $totalPendingPosts = Post::where('status', 'pending')->count();
        $freelancers = User::with('categories')->where('role', 'freelancer')->get(); 
        $customer = User::where('role', 'customer')->get();
        $users = User::all();
      $categories = Category::all();
      $posts = Post::with('freelancer')->where('status', 'pending')->get();
    
      // Get search input
    $search = $request->input('search');
    $section = $request->input('section', 'bookings');
    // Filter appointments by customer name if search is present
    $appointments = Appointment::with(['customer', 'freelancer'])
        ->when($section === 'bookings' && $search, function ($query) use ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('firstname', 'like', "%$search%")
                  ->orWhere('lastname', 'like', "%$search%");
            });
        })
        ->simplePaginate(10);

    // Violations Search (example: search by customer or freelancer name)
    $violations = Appointment::with(['customer', 'freelancer'])
        ->where(function ($query) use ($section, $search) {
            if ($section === 'violations' && $search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('firstname', 'like', "%$search%")
                      ->orWhere('lastname', 'like', "%$search%");
                })
                ->orWhereHas('freelancer', function ($q) use ($search) {
                    $q->where('firstname', 'like', "%$search%")
                      ->orWhere('lastname', 'like', "%$search%");
                });
            }
        })
        ->where('status', 'like', 'no_show%')
        ->simplePaginate(10);

    // User Stats Search (example: search by user name)
    $userStats = User::when($section === 'userstats' && $search, function ($query) use ($search) {
            $query->where('firstname', 'like', "%$search%")
                  ->orWhere('lastname', 'like', "%$search%");
        })
        ->simplePaginate(10);



      $totalRevenue = \App\Models\PlatformRevenue::sum('amount') ?? 0;
    
    $currentMonthRevenue = \App\Models\PlatformRevenue::whereMonth('date', now()->month)
        ->whereYear('date', now()->year)
        ->sum('amount') ?? 0;
        
    $revenueFromCompletions = \App\Models\PlatformRevenue::where('source', 'commitment_fee')
        ->sum('amount') ?? 0;
        
    // Get recent transactions
    $revenueTransactions = \App\Models\PlatformRevenue::with(['appointment.freelancer', 'user'])
        ->orderBy('date', 'desc')
        ->paginate(10);
        
    return View('dashboard.admin-dashboard', [
            'totalFreelancers' => $totalFreelancers,
            'totalCustomers' => $totalCustomers,
            'freelancers' => $freelancers,
            'customer' => $customer,
            'users' => $users,
           'categories' => $categories,
           'posts' => $posts,
           'totalPendingAccounts' => $totalPendingAccounts, 
           'totalPendingPosts' => $totalPendingPosts, 
           'appointments' => $appointments,  
           'search' => $search,
           'violations' => $violations,        // <-- Add this line
           'userStats' => $userStats,  
           'section' => $section,

           // Revenue data
            'totalRevenue' => $totalRevenue,
            'currentMonthRevenue' => $currentMonthRevenue,
            'revenueFromCompletions' => $revenueFromCompletions,
            'revenueTransactions' => $revenueTransactions
          
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


    public function banUser($id)
{
    $user = User::findOrFail($id);
    $user->is_banned = true;
    $user->save();
    return back()->with('success', 'User has been banned.');
}

public function unbanUser($id)
{
    $user = User::findOrFail($id);
    $user->is_banned = false;
    $user->save();
    return back()->with('success', 'User has been unbanned.');
}

// 
}
