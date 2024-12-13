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
          // Get the freelancer's first category (or modify as needed)
     $freelancerCategory = $user->categories()->first();
     $unreadNotifications = $user->unreadNotifications;

     $notifications = auth()->user()->notifications;
        
      // Fetch appointments related to the freelancer
      $appointments = Appointment::where('freelancer_id', $user->id)
      ->with('customer') // Load related customer data
      ->orderBy('created_at', 'desc')
      ->get();
       
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
           'totalAppointments' => $totalAppointments
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
    
    return redirect()->route('freelancer.dashboard', ['#appointments-section'])->with('success', 'Appointment accepted.');
}

public function declineAppointment($id)
{
    $appointment = Appointment::findOrFail($id);
    $appointment->status = 'declined';
    $appointment->save();

     // Notify the customer about the appointment status update
     $customer = $appointment->customer;
     if ($customer) {
         $customer->notify(new AppointmentStatusUpdated($appointment, 'declined'));
     }

    
    return redirect()->route('freelancer.dashboard', ['#appointments-section'])->with('success', 'Appointment declined.');
}

public function markAsCompleted($appointmentId)
{
    // Find the appointment by its ID
    $appointment = Appointment::find($appointmentId);

    // Check if the appointment exists and if the freelancer is the owner
    if ($appointment && $appointment->freelancer_id == Auth::id()) {
        // Update the status to "completed"
        $appointment->status = 'completed';
        $appointment->completed_at = now();  // Optionally, track when it was completed
        $appointment->save();

        // Notify the customer about the completion status (optional)
        $customer = $appointment->customer;
        if ($customer) {
            $customer->notify(new AppointmentStatusUpdated($appointment, 'completed'));
        }

        // Redirect back to the freelancer dashboard with a success message
        return redirect()->route('freelancer.dashboard', ['#appointments-section'])->with('success', 'The appointment has been marked as completed.');
    }

    // Redirect back with an error message if something goes wrong
    return redirect()->route('freelancer.dashboard', ['#appointments-section'])->with('error', 'Unable to mark the appointment as completed.');
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

    // Ensure the user has unread notifications of the specific type
    $user->unreadNotifications
         ->where('type', 'App\Notifications\AppointmentNotification')
         ->markAsRead();

    return redirect()->back()->with('success', 'Notifications marked as read.');
}

}


