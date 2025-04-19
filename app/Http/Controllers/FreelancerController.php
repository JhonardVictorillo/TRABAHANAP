<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Post;
use App\Models\Appointment;
use App\Notifications\AppointmentStatusUpdated; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;

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


        // Fetch notifications separately
        $appointmentNotifications = $user->notifications()
            ->where('type', 'App\Notifications\AppointmentNotification')
            ->get();

        $postNotifications = $user->notifications()
            ->where('type', 'App\Notifications\PostApprovalNotification')
            ->get();


     $notifications = auth()->user()->notifications;
        
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
            'totalClients' => $totalClients // pass total count
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
    $appointment = Appointment::findOrFail($id);
    $appointment->status = 'declined';
    $appointment->decline_reason = $request->input('reason');
    $appointment->save();

     // Notify the customer about the appointment status update
     $customer = $appointment->customer;
     if ($customer) {
         $customer->notify(new AppointmentStatusUpdated($appointment, 'declined'));
     }

     return response()->json([
        'success' => true,
        'message' => 'Appointment declined.',
        'status' => 'declined'
    ]);
}

public function getAppointments(Request $request)
{
    // Get the current freelancer's appointments (adjust this as needed)
    $appointments = Appointment::where('freelancer_id', auth()->id())->get();

    // Format appointments for FullCalendar
    $events = $appointments->map(function ($appointment) {
        return [
            'id' => $appointment->id,
            'title' => $appointment->name . ' (' . ucfirst($appointment->status) . ')', 
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
        'decline'  => '#ef4444', // red
        default     => '#6b7280', // gray
    };
}


public function show($id)
{
    $appointment = Appointment::findOrFail($id);

    return response()->json([
        'name' => $appointment->name ?? 'N/A',
        'date' => $appointment->date,
        'time' => $appointment->time,
        'address' => $appointment->address,
        'contact' => $appointment->contact ?? 'N/A',
        'notes' => $appointment->notes,
        'status' => ucfirst($appointment->status),
    ]);
}

public function markAsCompleted($appointmentId)
{
    
    $appointment = Appointment::find($appointmentId);

   
    if ($appointment && $appointment->freelancer_id == Auth::id()) {
        // Update the status to "completed"
        $appointment->status = 'completed';
        $appointment->completed_at = now();  // Optionally, track when it was completed
        $appointment->save();

       
        $customer = $appointment->customer;
        if ($customer) {
            $customer->notify(new AppointmentStatusUpdated($appointment, 'completed'));
        }

        return response()->json([
            'success' => true,
            'message' => 'The appointment has been marked as completed.',
            'status' => 'completed'
        ]);
    }

    return redirect()->back()->with('error', 'Unable to mark the appointment as completed.');
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
                        $notification->markAsRead(); // Use markAsRead() instead of update()

                        return response()->json([
                            'success' => true,
                            'message' => 'Notification marked as read.',
                            'unread_count' => $user->unreadNotifications()->count()
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

    $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'contact_number' => 'nullable|string',
        'province' => 'nullable|string',
        'city' => 'nullable|string',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user->firstname = $request->firstname;
    $user->lastname = $request->lastname;
    $user->email = $request->email;
    $user->contact_number = $request->contact_number;
    $user->province = $request->province;
    $user->city = $request->city;

    // Profile picture upload
    if ($request->hasFile('profile_picture')) {
        $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        $user->profile_picture = $imagePath;
    }

    $user->save();

    return redirect()->back()->with('success', 'Profile updated successfully!');
}

}


