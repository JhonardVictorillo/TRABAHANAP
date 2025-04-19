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
        $posts = Post::where('status', 'approved')
        ->whereHas('freelancer', function ($query) {
            $query->where('is_verified', true);  // Only include verified freelancers
        })
        ->with(['freelancer', 'freelancer.categories'])
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
     $freelancer = User::where('id', $id)
        ->where('is_verified', true)  // Only allow verified freelancers
        ->with(['posts'])
        ->firstOrFail();

    $user = Auth::user(); 
         // Fetch only reviews where a rating and review exist
    $reviews = Appointment::where('freelancer_id', $id)
    ->whereNotNull('rating') // Ensure there's a rating
    ->with('customer:id,firstname,lastname') // Load only customer name details
    ->get();
   
    return view('PostSeeProfile', compact('freelancer', 'user','reviews' ));
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
        'contact' =>  'required|string|regex:/^[0-9]{10}$/', 
        'notes' => 'nullable|string',
    ],[
      
        'contact.regex' => 'The contact number must be 10 digits.',
        'time.required' => 'The appointment time is required.',
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
        $posts = Post::where('status', 'approved')
        ->whereHas('freelancer', function ($query) {
            $query->where('is_verified', true);  // Only verified freelancers
        })
        ->with(['freelancer', 'freelancer.categories'])
        ->get();
    } else {
        // Search posts based on the query
        $posts = Post::where('status', 'approved')
        ->whereHas('freelancer', function ($query) {
            $query->where('is_verified', true); // Only verified freelancers
        })
        ->where(function ($query) use ($searchTerm) {
            $query->where('description', 'LIKE', '%' . $searchTerm . '%')
                ->orWhereHas('freelancer', function ($subQuery) use ($searchTerm) {
                    $subQuery->where('firstname', 'LIKE', '%' . $searchTerm . '%')
                             ->orWhere('lastname', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orWhereHas('freelancer.categories', function ($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
                });
        })
        ->with(['freelancer', 'freelancer.categories'])
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
    
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:500',
        ]);

        $appointment = Appointment::findOrFail($appointmentId);

        if ($appointment->customer_id == Auth::id()) {
            $request->validate([
                'rating' => 'required|integer|between:1,5',
                'review' => 'nullable|string|max:500',
            ]);
    
            $appointment->rating = $request->input('rating');
            $appointment->review = $request->input('review');
            $appointment->save();
    
            return response()->json(['success' => 'Review updated successfully!']);
        }
    
        return response()->json(['error' => 'You cannot edit this review.'], 403);
    }
}