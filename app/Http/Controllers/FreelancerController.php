<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Post;
use App\Models\Appointment;
use App\Notifications\AppointmentStatusUpdated; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use App\Models\FreelancerAvailability;
use App\Models\PlatformRevenue;
use Carbon\Carbon;
use App\Models\CategoryRequest;
use App\Notifications\NewCategoryRequestNotification;
use App\Notifications\CategoryRequestProcessedNotification;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Log;
use App\Models\FreelancerEarning;






class FreelancerController extends Controller
{
    /**
     * Show the Freelancer Dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $categories = Category::all(); 
        $userCategories = $user->categories; // Fetch user's selected categories
       $freelancerCategory = $user->categories()->first();
       $unreadNotifications = $user->unreadNotifications; // Get unread notifications

        $isProfileComplete = $user->profile_completed;
    // Fetch availabilities (no need to format start_time and end_time manually)
    $availabilities = $user->availabilities;

    
     $notifications = auth()->user()->notifications;
     $unreadCount = auth()->user()->unreadNotifications->count();  
      
     // Fetch appointments related to the freelancer
      $appointments = Appointment::where('freelancer_id', $user->id)
      ->with('customer') // Load related customer data
      ->orderBy('created_at', 'desc')
        ->paginate(6, ['*'], 'appointments_page');

      $clientAppointments = Appointment::where('freelancer_id', $user->id)
            ->whereIn('status', ['accepted', 'completed'])
            ->with('customer') // eager load customer
            ->get();
    
            // Collect unique customers from these appointments
        $uniqueClientIds = $clientAppointments->pluck('customer_id')->unique();
        $clients = User::whereIn('id', $uniqueClientIds)
            ->orderBy('created_at', 'desc')
            ->paginate(6, ['*'], 'clients_page');

        $totalClients = $uniqueClientIds->count();

     
         $reviews = Appointment::where('freelancer_id', $user->id)
        ->whereNotNull('rating')
        ->whereNotNull('review') // Also ensure there's a review text
        ->with('customer')
        ->orderBy('created_at', 'desc')
        ->paginate(6, ['*'], 'reviews_page');

      $averageRating = $this->calculateAverageRating($user->id);

      // Calculate the rating breakdown (e.g., 5-star, 4-star)
      $ratingBreakdown = $this->getRatingBreakdown($user->id);

      // Fetch posts related to the freelancer
           $posts = Post::where('freelancer_id', $user->id)->get(); 

           $posts = Post::where('freelancer_id', $user->id)
           ->with('appointments') // Eager load appointments for ratings
           ->get();


    // Fetch counts for posts and appointments
    $totalPosts = $user->posts()->count(); // Assuming you have a posts relationship
    $totalAppointments = $user->appointments()->count(); // Assuming you have an appointments relationship

    
     // Calculate completed services count
    $completedServices = $user->appointments()->where('status', 'completed')->count();

   // ===========================================
    // REVENUE CALCULATIONS - SIMPLIFIED & CLEAN
    // ===========================================

    // 1. SERVICE EARNINGS (Online Stripe payments only)
    $serviceEarnings = FreelancerEarning::where('freelancer_id', $user->id)
        ->whereIn('source', ['service_payment', 'commitment_fee', 'commitment_fee_bonus'])
        ->sum('amount');

    // 2. CASH EARNINGS (Cash payments only)
    $cashEarnings = FreelancerEarning::where('freelancer_id', $user->id)
        ->where('source', 'service_payment_cash')
        ->sum('amount');

    // 3. TOTAL EARNINGS (All payment sources - excluding withdrawals)
    $totalEarnings = FreelancerEarning::where('freelancer_id', $user->id)
        ->whereNotIn('source', ['withdrawal_request', 'withdrawal_cancelled'])
        ->sum('amount');

    // 4. CURRENT MONTH EARNINGS (All sources)
    $currentMonth = date('Y-m');
    $currentMonthEarnings = FreelancerEarning::where('freelancer_id', $user->id)
        ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
        ->whereNotIn('source', ['withdrawal_request', 'withdrawal_cancelled'])
        ->sum('amount');

    // 5. AVAILABLE BALANCE (Only online payments can be withdrawn)
    $onlineEarnings = FreelancerEarning::where('freelancer_id', $user->id)
        ->whereNotIn('source', ['service_payment_cash', 'withdrawal_request', 'withdrawal_cancelled'])
        ->sum('amount');
        
    $processedWithdrawals = Withdrawal::where('user_id', $user->id)
        ->whereIn('status', ['completed', 'processing'])
        ->sum('amount');
        
    $availableBalance = $onlineEarnings - $processedWithdrawals;

    // 6. WITHDRAWAL HISTORY
    $withdrawals = Withdrawal::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(10, ['*'], 'withdrawal_page'); 

    // 7. ALL PAYMENTS (Exclude withdrawals and violation-related entries)
    $allPayments = FreelancerEarning::where('freelancer_id', $user->id)
        ->whereNotIn('source', [
            'withdrawal_request', 
            'withdrawal_cancelled',
            'late_cancellation_fee',  // Remove since handled by violation system
            'no_show_fee'             // Remove since handled by violation system
        ])
        ->with(['appointment', 'appointment.customer', 'appointment.post', 'appointment.post.subservices'])
        ->orderBy('date', 'desc')
        ->paginate(10, ['*'], 'payment_page');

    // 8. MONTHLY EARNINGS FOR CHART (Exclude withdrawals only)
    $monthlyData = FreelancerEarning::select(
            DB::raw('DATE_FORMAT(date, "%Y-%m") as month'), 
            DB::raw('SUM(amount) as total')
        )
        ->where('freelancer_id', $user->id)
        ->whereNotIn('source', ['withdrawal_request', 'withdrawal_cancelled'])
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();
        
    $monthlyLabels = $monthlyData->pluck('month')->map(function($month) {
        return date('M Y', strtotime($month . '-01'));
    })->toArray();

    $monthlyEarnings = $monthlyData->pluck('total')->toArray();

        Log::info('Freelancer Earnings Debug', [
            'freelancer_id' => $user->id,
            'total_earnings' => $totalEarnings,
            'service_earnings' => $serviceEarnings,
            'all_earnings' => FreelancerEarning::where('freelancer_id', $user->id)->get(),
            'earnings_by_source' => FreelancerEarning::where('freelancer_id', $user->id)
                ->select('source', DB::raw('SUM(amount) as total'))
                ->groupBy('source')
                ->get()
        ]);
        // If profile is complete, show the dashboard without the modal
        return view('dashboard.freelancer-dashboard', [
            'user' => $user,
            'categories' => $categories, 
            'userCategories' => $userCategories, // Pass user's categories
            'freelancerCategory' => $freelancerCategory,
            'posts' => $posts, // Pass the posts data
            'unreadNotifications' => $unreadNotifications,
            'appointments' => $appointments,
            'averageRating' => $averageRating,
            'ratingBreakdown' => $ratingBreakdown,
           'notifications' => $notifications,
           'totalPosts' => $totalPosts,
           'totalAppointments' => $totalAppointments,
           'reviews' => $reviews,
            'clients' => $clients, // pass filtered clients
            'totalClients' => $totalClients,// pass total count
            'unreadCount' => $unreadCount,
            'availabilities' => $availabilities,
             'isProfileComplete' => $isProfileComplete,
              // NEW REVENUE/EARNINGS VARIABLES
            'serviceEarnings' => $serviceEarnings,      // Online payments only
            'cashEarnings' => $cashEarnings,            // Cash payments only  
            'totalEarnings' => $totalEarnings,          // All earnings combined
            'currentMonthEarnings' => $currentMonthEarnings,
            'availableBalance' => $availableBalance,    // Available for withdrawal
            'onlineEarnings' => $onlineEarnings,        // For display purposes
            'allPayments' => $allPayments,              // Clean payment history
            'monthlyLabels' => $monthlyLabels,
            'monthlyEarnings' => $monthlyEarnings,
            'completedServices' => $completedServices,
            'withdrawals' => $withdrawals,

            ]);

}   

public function showSelectedCategories()
{
    $user = Auth::user();
    
    // Retrieve only the categories associated with the authenticated user
    $userCategories = $user->categories; 
   
    return view('dashboard.selected-categories', [
        'user' => $user,
        'userCategories' => $userCategories,
      
    ]);
}
    
public function acceptAppointment($id)
{
    $appointment = Appointment::findOrFail($id);
    $appointment->status = 'accepted';
    $appointment->save();

    $customer = $appointment->customer;
    if ($customer) {
        $customer->notify(new AppointmentStatusUpdated($appointment, 'accepted'));
    }
    
   return response()->json([
            'success' => true,
            'message' => 'Appointment accepted.',
            'appointment_id' => $appointment->id,
            'status' => $appointment->status,
        ]);
}

public function declineAppointment(Request $request, $id)
{

    $request->validate([
        'reason' => 'required|string|max:255',
    ]);
    // Find the appointment by ID
    $appointment = Appointment::findOrFail($id);
    $appointment->status = 'declined';
    $appointment->decline_reason = $request->input('reason');
    $appointment->save();

     // Notify the customer about the appointment status update
     $customer = $appointment->customer;
     if ($customer) {
         $customer->notify(new AppointmentStatusUpdated($appointment, 'declined'));
     }

     \Log::info('Decline request input:', $request->all());
     \Log::info('Updated Appointment:', [
         'id' => $appointment->id,
         'status' => $appointment->status,
         'decline_reason' => $appointment->decline_reason,
     ]);

    return response()->json([
        'success' => true,
        'message' => 'Appointment declined.',
        'appointment_id' => $appointment->id,
        'status' => $appointment->status,
    ]);
}

public function getAppointments(Request $request)
{
    // Get the current freelancer's appointments (adjust this as needed)
    $appointments = Appointment::where('freelancer_id', auth()->id())
        ->with('customer') // Load the related customer data
        ->get();

    // Format appointments for FullCalendar
    $events = $appointments->map(function ($appointment) {
     
        // Format time with duration
        $startTime = $appointment->time;
        $duration = $appointment->duration ?? ($appointment->post ? $appointment->post->service_duration : 60);
        
        // Calculate end time
        $startTimeParts = explode(':', $startTime);
        $startHour = (int)$startTimeParts[0];
        $startMinute = isset($startTimeParts[1]) ? (int)$startTimeParts[1] : 0;
        
        $endHour = $startHour + floor($duration / 60);
        $endMinute = ($startMinute + $duration % 60) % 60;
        if ($startMinute + $duration % 60 >= 60) {
            $endHour++;
        }
        
        // Format for display
        $timeDisplay = sprintf(
            "%d:%02d - %d:%02d", 
            $startHour, $startMinute, $endHour, $endMinute
        );
        
        return [
            'id' => $appointment->id,
            'title' => $appointment->customer->firstname . ' ' . $appointment->customer->lastname . 
                     ' (' . ucfirst($appointment->status) . ')' .
                     '\n' . $timeDisplay,
            'start' => $appointment->date . 'T' . $appointment->time,
            'end' => $appointment->date . 'T' . sprintf("%02d:%02d:00", $endHour, $endMinute),
             'backgroundColor' => $this->getStatusColor($appointment->status),
            'borderColor' => $this->getStatusBorderColor($appointment->status),
            
            'extendedProps' => [
                'status' => $appointment->status,
                'time' => $appointment->time,
                'duration' => $duration,
                'customer_name' => $appointment->customer->firstname . ' ' . $appointment->customer->lastname,
                'address' => $appointment->address,
                'contact' => $appointment->customer->contact_number ?? 'N/A',
            ]
        ];
    });

    return response()->json($events);
}

// Optional helper for color-coding by status
private function getStatusColor($status)
{
    return match ($status) {
        'pending'   => '#fbbf24', // yellow
        'scheduled' => '#3b82f6', // blue
        'completed' => '#10b981', // green
        'declined'  => '#ef4444', // red
        default     => '#6b7280', // gray
    };
}

private function getStatusBorderColor($status)
{
    return match ($status) {
        'pending'   => '#d97706', // amber-600
        'accepted'  => '#1d4ed8', // blue-700
        'completed' => '#047857', // emerald-700
        'declined'  => '#b91c1c', // red-700
        'no_show'   => '#374151', // gray-700
        default     => '#6b7280', // gray-600
    };
}

public function show($id)
{
   try {
        $appointment = Appointment::with(['post.subServices', 'customer'])->findOrFail($id);

        return response()->json([
            'id' => $appointment->id,
            'name' => $appointment->customer->firstname.' '.$appointment->customer->lastname,
            'date' => $appointment->date,
            'time' => $appointment->time,
            'address' => $appointment->address,
            'contact' => $appointment->customer->contact_number ?? 'N/A',
            'status' => $appointment->status,
            'final_payment_status' => $appointment->final_payment_status,
            'total_amount' => $appointment->total_amount,
            'commitment_fee' => $appointment->commitment_fee ?? 0,
            'fee_status' => $appointment->fee_status,
            'notes' => $appointment->notes ?? 'No notes provided',
            'duration' => $appointment->duration ?? ($appointment->post ? $appointment->post->service_duration : 60),
            'buffer_time' => $appointment->post ? $appointment->post->buffer_time : 0,
            'subservices' => $appointment->post && $appointment->post->subServices
                ? $appointment->post->subServices->pluck('sub_service')->toArray()
                : [],
              'customer_profile_picture' => $appointment->customer->profile_picture,
            'customer_email' => $appointment->customer->email,
          
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching appointment details: ' . $e->getMessage());
        return response()->json(['error' => 'Appointment not found'], 404);
    }
}

public function markAsCompleted($appointmentId)
{
    try {
        $appointment = Appointment::find($appointmentId);

        if (!$appointment || $appointment->freelancer_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to mark the appointment as completed.'
            ], 403);
        }

        // Only update status and completion time
        $appointment->status = 'completed';
        $appointment->completed_at = now();
        $appointment->save();

        // Notify the customer
        $customer = $appointment->customer;
        if ($customer) {
            $customer->notify(new AppointmentStatusUpdated($appointment, 'completed'));
        }

        return response()->json([
            'success' => true,
            'message' => 'The appointment has been marked as completed.',
            'status' => 'completed'
        ]);
    } catch (\Exception $e) {
        \Log::error('Error completing appointment: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
}
    
 // Method to calculate the average rating
        private function calculateAverageRating($freelancerId)
        {
            $appointments = Appointment::where('freelancer_id', $freelancerId)
                ->whereNotNull('rating') // Only consider appointments with a rating
                ->get();

            // Ensure there's at least one rating to avoid division by zero
            return $appointments->isEmpty() ? 0 : round($appointments->avg('rating'), 1);
        }

        // Method to calculate the rating breakdown (1-5 stars)
        private function getRatingBreakdown($freelancerId)
        {
            $appointments = Appointment::where('freelancer_id', $freelancerId)
                ->whereNotNull('rating') // Only consider appointments with a rating
                ->get();

            // Group by rating and return the count for each rating
            return collect([1, 2, 3, 4, 5])->mapWithKeys(function ($rating) use ($appointments) {
                return [$rating => $appointments->where('rating', $rating)->count()];
            });
}



public function markNotificationsAsRead()
{
    $user = Auth::user();

    // Mark both types of notifications as read
    $user->unreadNotifications()->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read.',
            'unread_count' => $user->unreadNotifications()->count(), // This should now be 0
            'notifications' => $user->fresh()->notifications // Return all notifications (both read and unread)
        ]);
}

public function markSingleNotificationAsRead($id)
{
    $user = Auth::user();
    $notification = $user->notifications()->where('id', $id)->first();

    if ($notification && is_null($notification->read_at)) {
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
            'unread_count' => $user->unreadNotifications->count(),
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
}


public function getNotifications() {
    return response()->json(Auth::user()->notifications);
}

public function update(Request $request)
{
    $user = Auth::user();

    // Validate the request data with expanded validation rules
    $validated = $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'bio' => 'nullable|string|max:300',
        'contact_number' => 'nullable|string|max:20',
        'province' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'zipcode' => 'nullable|string|max:20',
        'barangay' => 'nullable|string|max:255',
        'experience_years' => 'required|integer|min:0|max:100',
        'specialization' => 'nullable|string|max:255',
        'skills' => 'nullable|array',
        'skills.*' => 'string|max:50',
        'category_request' => 'nullable|string|max:255',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'categories' => 'nullable|array',
        'categories.*' => 'exists:categories,id',
    ]);

    // Profile picture upload with proper error handling
    if ($request->hasFile('profile_picture')) {
        try {
            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $imagePath;
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload profile picture: ' . $e->getMessage());
        }
    }

    // Handle skills array - convert to JSON for storage
    if (isset($validated['skills']) && is_array($validated['skills'])) {
        $user->skills = json_encode($validated['skills']);
    } elseif (!$request->has('skills')) {
        $user->skills = null;
    }
    
    // Handle category request (only if it's new and user doesn't already have a pending request)
    if ($request->filled('category_request') && 
        $request->category_request != $user->category_request && 
        !$user->category_request) {
        
        try {
            // Create a record in category_requests table
            $categoryRequest = new CategoryRequest();
            $categoryRequest->user_id = $user->id;
            $categoryRequest->category_name = $request->category_request;
            $categoryRequest->status = 'pending';
            $categoryRequest->save();
            
            // Notify admins about the new request
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
             $admin->notify(new NewCategoryRequestNotification($user, $request->category_request));
            }
        } catch (\Exception $e) {
            // Continue with the update even if notification fails
            \Log::error('Failed to process category request: ' . $e->getMessage());
        }
    }
    
    // Handle categories relationship if present
    if ($request->has('categories')) {
        $user->categories()->sync($request->categories);
    }

    // Update user attributes
    $user->fill(array_diff_key($validated, array_flip(['profile_picture', 'skills', 'categories'])));
    $user->save();

    return redirect()->back()->with('success', 'Profile updated successfully!');
}
public function setAvailability(Request $request)
{
   

    $request->validate([
        'date' => 'required|date',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
    ]);

    // Calculate the day of the week
    $dayOfWeek = Carbon::parse($request->date)->format('l'); // e.g., "Monday"

    FreelancerAvailability::create([
        'freelancer_id' => Auth::id(),
        'date' => $request->date,
        'day_of_week' => $dayOfWeek, // Automatically populate day_of_week
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
    ]);

    return redirect()->back()->with('success', 'Availability added successfully!');
}

public function editAvailability($id)
{
    $availability = FreelancerAvailability::findOrFail($id);
    return response()->json($availability);
}



public function updateAvailability(Request $request, $id)
{
    $request->validate([
        'date' => 'required|date',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
    ]);

    $availability = FreelancerAvailability::findOrFail($id);

    // Calculate the day of the week
    $dayOfWeek = Carbon::parse($request->date)->format('l'); // e.g., "Monday"

    $availability->update([
        'date' => $request->date,
        'day_of_week' => $dayOfWeek,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Schedule updated successfully!',
        'availability' => $availability,
    ]);
}
public function destroyAvailability($id)
{
    $availability = FreelancerAvailability::findOrFail($id);
    $availability->delete();

    return response()->json(['success' => true]);
}

public function updatePaymentSettings(Request $request)
{
    $user = auth()->user();
    
    // Update auto-transfer setting
    $user->auto_transfer_enabled = $request->has('auto_transfer_enabled');
    $user->save();
    
    return redirect()->back()->with('success', 'Payment settings updated successfully');
}


public function viewPost($id)
{
    try {
        $post = Post::with(['subServices', 'freelancer.categories', 'pictures'])
            ->where('id', $id)
            ->where('freelancer_id', Auth::id())
            ->firstOrFail();

        // Get post pictures
        $postPictures = $post->pictures->pluck('image_path')->toArray();

        // Get sub-services
        $subServices = $post->subServices->pluck('sub_service')->toArray();

        // Get category
        $category = $post->freelancer->categories->first()->name ?? 'Not assigned';

        // Get performance data with proper null handling
        $averageRating = $post->averageRating() ?? 0;
        $totalReviews = $post->totalReviews() ?? 0;
        $totalBookings = $post->appointments()->count() ?? 0;

        // Ensure averageRating is a valid number
        if (!is_numeric($averageRating)) {
            $averageRating = 0;
        }

        return response()->json([
            'post' => $post,
            'post_pictures' => $postPictures,
            'sub_services' => $subServices,
            'category' => $category,
            'performance' => [
                'average_rating' => (float) $averageRating,
                'total_reviews' => (int) $totalReviews,
                'total_bookings' => (int) $totalBookings
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Post not found'], 404);
    }
}
}


