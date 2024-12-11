<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\Appointment;
use App\Notifications\AppointmentRequest;

use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Show the Customer Dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $categories = Category::all(); 
        $posts = Post::with(['freelancer', 'freelancer.categories'])
        ->withCount(['appointments as review_count' => function ($query) {
            $query->whereNotNull('rating');
        }])
        ->withAvg('appointments as average_rating', 'rating')
        ->get();

        $notifications = auth()->user()->notifications; // Get all notifications
    
        // If profile is complete, show the dashboard without the modal
        return view('dashboard.customer-dashboard', [
            'user' => $user,
            'categories' => $categories, 
           'posts' => $posts,
           'notifications' => $notifications
        ]);
    }

    public function showProfile()
{
    // Fetch the authenticated user's data
    $user = auth()->user();

    // Pass the data to the profile view
    return view('customerProfile', compact('user'));
}

public function showFreelancerProfile($id)
{
    $freelancer = User::with('posts')->findOrFail($id); // Assuming 'posts' is the relationship for recent works
    return view('PostSeeProfile', compact('freelancer'));
}
public function bookAppointment(Request $request)
{
    $validated = $request->validate([
        'freelancer_id' => 'required|exists:users,id',  
        'post_id' => 'required|exists:posts,id',
        'date' => 'required|date',
        'time' => 'required|string',
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'contact' => 'required|string|max:20',
        'notes' => 'nullable|string',
    ]);
   
   $appointment = Appointment::create([
        'freelancer_id' => $validated['freelancer_id'],
        'customer_id' => auth()->id(),
        'post_id' => $validated['post_id'],
        'date' => $validated['date'],
        'time' => $validated['time'],
        'name' => $validated['name'],
        'address' => $validated['address'],
        'contact' => $validated['contact'],
        'notes' => $validated['notes'],
        'status' => 'pending', // Default status for new appointments
    ]);

            // Notify the freelancer
        $freelancer = User::find($validated['freelancer_id']);
        $freelancer->notify(new AppointmentRequest($appointment));

        
        session()->flash('success', 'Appointment booked successfully!');
    return redirect()->back()->with('success', 'Appointment request sent successfully!');
}


public function showAppointments()
{
    $appointments = Appointment::where('customer_id', auth()->id())
    ->with(['freelancer', 'post', 'freelancer.categories'])
    ->get();

    return view('customerAppointments', compact('appointments'));
}

public function cancelAppointment($id)
{
    $appointment = Appointment::where('id', $id)->where('customer_id', auth()->id())->firstOrFail();
    $appointment->status = 'Canceled'; // Update the status
    $appointment->save();

    return redirect()->route('customer.appointments')->with('success', 'Appointment canceled successfully.');
}


public function search(Request $request)
{
    $searchTerm = $request->query('q');

    if (empty($searchTerm)) {
        // If no search term is provided, fetch all posts
        $posts = Post::with(['freelancer', 'freelancer.categories'])->get();
    } else {
        // Search posts based on the query
        $posts = Post::with(['freelancer', 'freelancer.categories'])
            ->where('description', 'LIKE', '%' . $searchTerm . '%')
            ->orWhereHas('freelancer', function ($query) use ($searchTerm) {
                $query->where('firstname', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('lastname', 'LIKE', '%' . $searchTerm . '%');
            })
            ->orWhereHas('freelancer.categories', function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', '%' . $searchTerm . '%');
            })
            ->get();
    }

    // Fetch notifications for the logged-in user
    $notifications = auth()->user()->unreadNotifications;
    // Fetch the authenticated user
    $user = auth()->user();

    return view('dashboard.customer-dashboard', [
        'posts' => $posts,
        'searchTerm' => $searchTerm,
        'user' => $user,
        'notifications' => $notifications,
    ]);
}


public function rateAppointment($appointmentId, Request $request)
{
    $appointment = Appointment::findOrFail($appointmentId);

    // Check if the appointment belongs to the authenticated customer
    if ($appointment->customer_id == Auth::id()) {
        // Validate the rating input
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        // Update the appointment with the rating
        $appointment->rating = $request->input('rating');
        $appointment->save();

        // Redirect back with a success message
        return redirect()->route('customer.appointments')->with('success', 'Thank you for your rating!');
    }

    return redirect()->route('customer.appointments')->with('error', 'You cannot rate this appointment.');
}

}