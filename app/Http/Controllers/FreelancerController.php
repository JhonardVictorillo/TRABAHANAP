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
use App\Models\User;
use Illuminate\Support\Facades\Storage;



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

        // Fetch notifications separately
        $appointmentNotifications = $user->notifications()
            ->where('type', 'App\Notifications\AppointmentNotification')
            ->get();

        $postNotifications = $user->notifications()
            ->where('type', 'App\Notifications\PostApprovalNotification')
            ->get();


     $notifications = auth()->user()->notifications;
     $unreadCount = auth()->user()->unreadNotifications->count();  
      // Fetch appointments related to the freelancer
      $appointments = Appointment::where('freelancer_id', $user->id)
      ->with('customer') // Load related customer data
      ->orderBy('created_at', 'desc')
      ->get();

      $clientAppointments = Appointment::where('freelancer_id', $user->id)
    ->whereIn('status', ['accepted', 'completed'])
    ->with('customer') // eager load customer
    ->get();
    
            // Collect unique customers from these appointments
        $clients = $clientAppointments->pluck('customer')->unique('id')->values();

        // Optional count of total clients
        $totalClients = $clients->count();

     
      $reviews = $appointments->whereNotNull('rating');
       
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

    
    
         // Total revenue from all sources
    $totalRevenue = PlatformRevenue::sum('amount') ?? 0;
    
    // Revenue specifically from late cancellations and no-shows
    $lateCancellationRevenue = PlatformRevenue::where('source', 'late_cancellation_fee')
        ->sum('amount') ?? 0;
        
    $noShowRevenue = PlatformRevenue::where('source', 'no_show_fee')
        ->sum('amount') ?? 0;
        
    // Combined late cancellation and no-show revenue
    $cancellationAndNoShowRevenue = $lateCancellationRevenue + $noShowRevenue;
    
    // Current month's late cancellation and no-show revenue
    $currentMonthCancellationRevenue = PlatformRevenue::whereIn('source', ['late_cancellation_fee', 'no_show_fee'])
        ->whereMonth('date', now()->month)
        ->whereYear('date', now()->year)
        ->sum('amount') ?? 0;
    
    // Only include late cancellation and no-show transactions
    $revenueTransactions = PlatformRevenue::whereIn('source', ['late_cancellation_fee', 'no_show_fee'])
        ->with(['appointment.freelancer', 'user'])
        ->orderBy('date', 'desc')
        ->paginate(10);
    




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
           'appointmentNotifications' => $appointmentNotifications,
            'postNotifications' => $postNotifications,
             'reviews' => $reviews,
            'clients' => $clients, // pass filtered clients
            'totalClients' => $totalClients,// pass total count
            'unreadCount' => $unreadCount,
            'availabilities' => $availabilities,
            'totalRevenue' => $totalRevenue,
            'cancellationAndNoShowRevenue' => $cancellationAndNoShowRevenue,
            'currentMonthCancellationRevenue' => $currentMonthCancellationRevenue,
            'revenueTransactions' => $revenueTransactions,  
            'lateCancellationRevenue' => $lateCancellationRevenue,
            'noShowRevenue' => $noShowRevenue,
             'isProfileComplete' => $isProfileComplete,
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
        'status' => 'accepted'
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
        'status' => 'declined'
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
        return [
            'id' => $appointment->id,
            'title' => $appointment->customer->firstname . ' ' . $appointment->customer->lastname . ' (' . ucfirst($appointment->status) . ')',
            'start' => $appointment->date,
            'color' => $this->getStatusColor($appointment->status), 
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


public function show($id)
{
    $appointment = Appointment::findOrFail($id);


    return response()->json([
        'id' => $appointment->id,
        'name' => $appointment->customer->firstname.' '.$appointment->customer->lastname, // Ensure the customer's name is included
        'date' => $appointment->date,
        'time' => $appointment->time,
        'address' => $appointment->address,
        'contact' => $appointment->customer->contact_number, // Assuming contact is part of the customer model
        'status' => $appointment->status,
        'notes' => $appointment->notes, // Include the decline reason
    ]);
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

        // Update the status to "completed"
        $appointment->status = 'completed';
        $appointment->completed_at = now();  // Track when it was completed
        
        // Handle the commitment fee as platform commission
        if ($appointment->fee_status === 'paid') {
            $appointment->fee_status = 'commission_collected';
            
            // Record as platform revenue
            \App\Models\PlatformRevenue::create([
                'amount' => $appointment->commitment_fee ?? 10.00, // Use actual amount or default
                'source' => 'commitment_fee',
                'appointment_id' => $appointment->id,
                'user_id' => $appointment->customer_id,
                'date' => now(),
                'notes' => 'Commitment fee from completed appointment'
            ]);
            
            // Log the transaction
            \Log::info("Commitment fee collected as platform commission", [
                'appointment_id' => $appointment->id,
                'amount' => $appointment->commitment_fee_amount ?? 10.00
            ]);
        }
        
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
    $user->unreadNotifications()
        ->whereIn('type', [
            'App\Notifications\AppointmentNotification',
            'App\Notifications\PostApprovalNotification'
        ])
        ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read.',
            'unread_count' => $user->unreadNotifications()->count(), // This should now be 0
            'notifications' => $user->notifications // Return all notifications (both read and unread)
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
        'google_map_link' => 'nullable|url|max:255',
        'experience_level' => 'required|string|in:Beginner,Intermediate,Expert',
        'specialization' => 'nullable|string|max:255',
        'skills' => 'nullable|array',
        'skills.*' => 'string|max:50',
        'hourly_rate' => 'nullable|numeric|min:0',
        'daily_rate' => 'nullable|numeric|min:0',
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

}


