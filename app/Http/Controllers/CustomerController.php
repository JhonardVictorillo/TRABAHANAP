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
    $notifications = $user->notifications; 
    $recentFreelancers = User::where('role', 'freelancer')
    ->where('is_verified', true)
    ->with('categories') // Load categories relationship
    ->latest() // Order by the most recent
    ->take(6) // Limit to 6 freelancers
    ->get();
    // Pass the data to the profile view
    return view('customerProfile', compact('user','recentFreelancers', 'notifications'));
}

public function showFreelancerProfile($id)
{
     $freelancer = User::where('id', $id)
        ->where('is_verified', true)  // Only allow verified freelancers
        ->with(['posts.pictures'])
        ->firstOrFail();

    $user = Auth::user(); 
    $notifications = $user->notifications;
         // Fetch only reviews where a rating and review exist
    $reviews = Appointment::where('freelancer_id', $id)
    ->whereNotNull('rating') // Ensure there's a rating
    ->with('customer:id,firstname,lastname') // Load only customer name details
    ->get();
    $totalStars = $reviews->sum('rating'); // Sum of all ratings
    $totalReviews = $reviews->count();
   
    
   
    return view('PostSeeProfile', compact('freelancer', 'user','reviews', 'notifications','totalStars', 'totalReviews' ));
}

public function bookAppointment(Request $request)
{
   
    // Validate the input fields from the request
    $validated = $request->validate([
        'freelancer_id' => 'required|exists:users,id',  
        'post_id' => 'required|exists:posts,id',
        'date' => 'required|date',
        'time' => 'required|string',
        'notes' => 'nullable|string',
    ], [
        'time.required' => 'The appointment time is required.',
    ]);

    // Fetch the authenticated user's details
    $user = auth()->user();

    // Automatically use the authenticated user's details for name, address, and contact
    $appointment = Appointment::create([
        'freelancer_id' => $validated['freelancer_id'],
        'customer_id' => $user->id,
        'post_id' => $validated['post_id'],
        'date' => $validated['date'],
        'time' => $validated['time'],
        'name' => $user->firstname . ' ' . $user->lastname, // Combine first and last name
        'address' => $user->province.' '.$user->city ?? 'N/A', // Use the user's address or a default value
        'contact' => $user->contact_number ?? 'N/A', // Use the user's contact number or a default value
        'notes' => $validated['notes'],
        'status' => 'pending', // Default status for new appointments
    ]);

   
    // Notify the freelancer
    $freelancer = User::find($validated['freelancer_id']);
    $freelancer->notify(new AppointmentRequest($appointment));

    // Flash success message and redirect back
    session()->flash('success', 'Appointment booked successfully!');
    return redirect()->back()->with('success', 'Appointment request sent successfully!');
}
private function getStatusColor($status)
{
    return match ($status) {
        'pending'   => '#fbbf24', // Yellow
        'completed' => '#10b981', // Green
        'canceled'  => '#ef4444', // Red
        default     => '#6b7280', // Gray
    };
}


public function showAppointments()
{

    $user = auth()->user();
    $notifications = $user->notifications;
    return view('customerAppointments', compact('user','notifications'));
    
}


public function getAppointments(Request $request)
{
    // Get the current freelancer's appointments (adjust this as needed)
    $appointments = Appointment::with('freelancer') // Ensure the freelancer relationship is loaded
    ->where('customer_id', auth()->id()) // Fetch appointments for the authenticated customer
    ->get();

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

    public function getAppointmentDetails($id)
    {
        $appointment = Appointment::with('freelancer') // Load freelancer relationship
        ->where('id', $id)
        ->where('customer_id', auth()->id()) // Ensure the appointment belongs to the authenticated customer
        ->firstOrFail();

        return response()->json([
            'id' => $appointment->id,
            'freelancer_name' => $appointment->freelancer->firstname . ' ' . $appointment->freelancer->lastname,
            'date' => $appointment->date,
            'time' => $appointment->time,
            'address' => $appointment->address,
            'contact' => $appointment->contact ?? 'N/A',
            'notes' => $appointment->notes,
            'status' => ucfirst($appointment->status),
            'decline_reason' => $appointment->decline_reason,
        ]);
    }

    public function cancelAppointment($id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('customer_id', auth()->id())
            ->firstOrFail();
    
        $appointment->status = 'Canceled';
        $appointment->save();
    
        // Notify the freelancer
        $freelancer = $appointment->freelancer;
        $freelancer->notify(new AppointmentCanceled($appointment)); // Create a notification for this
    
        return response()->json(['message' => 'Appointment canceled successfully.']);
    }

    public function rescheduleAppointment(Request $request, $id)
{
    try {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required|string',
        ]);

        $appointment = Appointment::where('id', $id)
            ->where('customer_id', auth()->id()) // Ensure the appointment belongs to the authenticated customer
            ->firstOrFail();

        $appointment->date = $validated['date'];
        $appointment->time = $validated['time'];
        $appointment->save();

        // Notify the freelancer about the reschedule
        // $freelancer = $appointment->freelancer;
        // $freelancer->notify(new AppointmentRescheduled($appointment)); // Create a notification for this

        return response()->json(['message' => 'Appointment rescheduled successfully!']);
    } catch (\Exception $e) {
        // Log the error for debugging
        \Log::error('Error rescheduling appointment: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to reschedule appointment.'], 500);
    }
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
            // Validate the input
            $validated = $request->validate([
                'rating' => 'required|integer|between:1,5',
                'review' => 'nullable|string|max:500',
            ]);

            // Find the appointment and ensure it belongs to the authenticated customer
            $appointment = Appointment::where('id', $appointmentId)
                ->where('customer_id', Auth::id())
                ->firstOrFail();

            // Update the rating and review
            $appointment->rating = $validated['rating'];
            $appointment->review = $validated['review'];
            $appointment->save();

            // Return a JSON response with a "message" key
            return response()->json(['message' => 'Review submitted successfully!']);
        }



        public function markNotificationAsRead($id)
{
    $notification = auth()->user()->unreadNotifications->find($id);

    if ($notification) {
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
}

public function markAllNotificationsAsRead()
{
    auth()->user()->unreadNotifications->markAsRead();
    return response()->json(['success' => true]);
}

public function updateProfile(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'contact_number' => 'nullable|string|max:15',
        'province' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'zipcode' => 'required|string|max:10',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user->firstname = $request->firstname;
    $user->lastname = $request->lastname;
    $user->email = $request->email;
    $user->contact_number = $request->contact_number;
    $user->province = $request->province;
    $user->city = $request->city;
    $user->zipcode = $request->zipcode;

    if ($request->hasFile('profile_picture')) {
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        $user->profile_picture = $path;
    }

    $user->save();

    return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
}
}