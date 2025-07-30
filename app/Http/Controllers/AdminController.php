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
use App\Models\CategoryRequest; 
use App\Models\Withdrawal;
use Stripe\Stripe;
use Stripe\Payout;
use App\Models\FreelancerEarning;
use App\Models\PlatformWithdrawal; // Import the PlatformWithdrawal model
use App\Notifications\WithdrawalProcessedNotification;
use App\Notifications\WithdrawalRejectedNotification;


class AdminController extends Controller
{
   public function dashboard(Request $request){
        $totalFreelancers = User::where('role', 'freelancer')->count();
        $totalCustomers = User::where('role', 'customer')->count();

        // Fetch pending accounts (freelancers with is_verified = 0)
        $totalPendingAccounts = User::where('role', 'freelancer')->where('is_verified', 0)->count();

         // Fetch notifications for admin
        $notifications = auth()->user()->notifications()->latest()->take(10)->get();
        $unreadCount = auth()->user()->unreadNotifications->count();
    
        // Fetch pending posts (posts with status = 'pending')
        $totalPendingPosts = Post::where('status', 'pending')->count();
        $freelancers = User::with('categories')->where('role', 'freelancer')->get(); 
        $customer = User::where('role', 'customer')->get();
        $users = User::all();
    
      $posts = Post::with('freelancer')->where('status', 'pending')->get();
    
       $categories = Category::withCount('users')->orderBy('name')->simplePaginate(10);
           // Count all types of requests for filter badges
        $categoryRequestsCount = CategoryRequest::count();
        $pendingCount = CategoryRequest::where('status', 'pending')->count();
        $approvedCount = CategoryRequest::where('status', 'approved')->count();
        $declinedCount = CategoryRequest::where('status', 'declined')->count();
        // For category requests - initialize as empty
        $categoryRequests = collect([]); // Use a simple collection for now

        // If tab is set to requests, get category requests with simple pagination
        if ($request->tab == 'requests') {
            $requestsQuery = CategoryRequest::with('user');
            
            if ($request->has('status')) {
                $requestsQuery->where('status', $request->status);
            }
            
            $categoryRequests = $requestsQuery->orderBy('created_at', 'desc')->simplePaginate(10);
        } else {
            // Create a simple empty paginator
            $categoryRequests = new \Illuminate\Pagination\Paginator(
                collect([]), // Empty collection
                10,          // Per page
                1,           // Current page
                [
                    'path' => request()->url(),
                    'pageName' => 'page_req' // Use a different page name to avoid conflicts
                ]
            );
        }



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
             'categoryRequests' => $categoryRequests, // Pass category requests to the view
           // Revenue data
            'totalRevenue' => $totalRevenue,
            'currentMonthRevenue' => $currentMonthRevenue,
            'revenueFromCompletions' => $revenueFromCompletions,
            'revenueTransactions' => $revenueTransactions,
            'categoryRequestsCount' => $categoryRequestsCount,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'declinedCount' => $declinedCount,
             'notifications' => $notifications,
            'unreadCount' => $unreadCount,
          
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

// Add these new methods to handle notification actions
public function getNotifications()
{
    try {
        $notifications = auth()->user()->notifications()->latest()->get();
        $unreadCount = auth()->user()->unreadNotifications->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching admin notifications: ' . $e->getMessage());
        return response()->json(['error' => 'Could not fetch notifications'], 500);
    }
}

/**
 * Mark a specific notification as read
 */
public function markSingleNotificationAsRead($id)
{
    try {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }
        
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'unreadCount' => auth()->user()->unreadNotifications->count()
        ]);
    } catch (\Exception $e) {
        \Log::error('Error marking admin notification as read: ' . $e->getMessage());
        return response()->json(['error' => 'Could not mark notification as read'], 500);
    }
}

/**
 * Mark all notifications as read
 */
public function markNotificationsAsRead()
{
    try {
        auth()->user()->unreadNotifications->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    } catch (\Exception $e) {
        \Log::error('Error marking all admin notifications as read: ' . $e->getMessage());
        return response()->json(['error' => 'Could not mark all notifications as read'], 500);
    }
}


 public function resolveDispute(Request $request, $id)
    {
        $request->validate([
            'resolution' => 'required|in:refund,complete_payment,partial_payment',
            'notes' => 'required|string',
            'partial_amount' => 'required_if:resolution,partial_payment|numeric|min:0'
        ]);
        
        $appointment = Appointment::findOrFail($id);
        
        // Handle the dispute resolution
        switch ($request->resolution) {
            case 'refund':
                // Code to issue refund
                $appointment->final_payment_status = 'refunded';
                break;
                
            case 'complete_payment':
                // Code to complete the payment
                $appointment->final_payment_status = 'paid';
                
                // Calculate final payment amount (total minus commitment fee)
                $finalPaymentAmount = $appointment->total_amount - $appointment->commitment_fee;
                
                // Record platform revenue (10% commission)
                PlatformRevenue::create([
                    'user_id' => $appointment->freelancer_id,
                    'appointment_id' => $appointment->id,
                    'amount' => $finalPaymentAmount * 0.1, // 10% platform fee
                    'source' => 'final_payment_commission',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'notes' => 'Platform commission from final payment (dispute resolution)'
                ]);
                
                // Record freelancer earning (90% of final payment)
                FreelancerEarning::create([
                    'freelancer_id' => $appointment->freelancer_id,
                    'appointment_id' => $appointment->id,
                    'amount' => $finalPaymentAmount * 0.9, // 90% to freelancer
                    'source' => 'service_payment',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'notes' => "Final payment for appointment #{$appointment->id} (dispute resolution)"
                ]);
                break;
                
            case 'partial_payment':
                // Code to handle partial payment
                $appointment->final_payment_status = 'partially_paid';
                
                // Calculate partial amount for freelancer
                $platformFeePercentage = 10;
                $partialAmount = $request->partial_amount;
                $freelancerAmount = $partialAmount * (1 - $platformFeePercentage/100);
                
                // Record partial payment for freelancer
                FreelancerEarning::create([
                    'freelancer_id' => $appointment->freelancer_id,
                    'appointment_id' => $appointment->id,
                    'amount' => $freelancerAmount,
                    'source' => 'partial_payment',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'notes' => "Partial payment from dispute resolution"
                ]);
                
                // Record platform commission
                PlatformRevenue::create([
                    'user_id' => $appointment->freelancer_id,
                    'appointment_id' => $appointment->id,
                    'amount' => $partialAmount * ($platformFeePercentage/100),
                    'source' => 'partial_payment_commission',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'notes' => "Platform commission from partial payment"
                ]);
                break;
        }
        
        $appointment->save();
        
        // Record resolution notes
        DB::table('admin_notes')->insert([
            'appointment_id' => $appointment->id,
            'note' => "Dispute resolved: {$request->resolution}. Notes: {$request->notes}",
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->back()->with('success', 'Dispute resolved successfully.');
    }
}
