<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\Appointment;
use App\Models\FreelancerAvailability;
use App\Notifications\AppointmentRequest;
use App\Notifications\AppointmentCanceled;
use App\Notifications\AppointmentRescheduled;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PlatformRevenue;
use App\Models\FreelancerEarning;




class CustomerController extends Controller
{
    /**
     * Show the Customer Dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $categories = Category::all(); 
        // Get the request object
    $request = request();
    
    // Start with base query
    $query = Post::where('status', 'approved')
        ->whereHas('freelancer', function ($query) {
            $query->where('is_verified', true);
        })
        ->with(['freelancer', 'freelancer.categories'])
        ->withCount(['appointments as review_count' => function ($query) {
            $query->whereNotNull('rating');
        }])
        ->withAvg('appointments as average_rating', 'rating');

    // Category filter
    if ($request->filled('category')) {
        $categoryId = $request->category;
        $query->whereHas('freelancer.categories', function($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }
    
    // Price filter - using either hourly_rate or price depending on your structure
    if ($request->filled('price_range')) {
    $priceRange = $request->price_range;
    
    // Use whereHas to filter based on the related user's hourly_rate
    if ($priceRange == 'low') {
        $query->whereHas('freelancer', function($q) {
            $q->where('hourly_rate', '<', 1000);
        });
    } elseif ($priceRange == 'medium') {
        $query->whereHas('freelancer', function($q) {
            $q->whereBetween('hourly_rate', [1000, 3000]);
        });
    } elseif ($priceRange == 'high') {
        $query->whereHas('freelancer', function($q) {
            $q->where('hourly_rate', '>', 3000);
        });
    }
}
    
    // Experience level filter
    if ($request->filled('experience')) {
        $experience = $request->experience;
        $query->whereHas('freelancer', function($q) use ($experience) {
            $q->where('experience_level', $experience);
        });
    }
    
    // Location filter
    if ($request->filled('location')) {
        $location = $request->location;
        $query->whereHas('freelancer', function($q) use ($location) {
            $q->where('city', 'like', "%{$location}%")
              ->orWhere('province', 'like', "%{$location}%");
        });
    }
    
    // Rating filter - using a safer approach
    if ($request->filled('rating') && is_numeric($request->rating)) {
        $minRating = (float)$request->rating;
        // First get all results
        $posts = $query->paginate(12);
        
        // Then manually filter by rating
        $posts->setCollection($posts->getCollection()->filter(function($post) use ($minRating) {
            return $post->average_rating >= $minRating;
        }));
    } else {
        // Normal pagination without rating filter
        $posts = $query->paginate(12);
    }
    
    // Sort options
            // Sorting logic in your dashboard method
            if ($request->filled('sort')) {
                $sort = $request->sort;
                
                if ($sort == 'price_low') {
                    // Use hourly_rate from users table instead of price
                    $query->join('users', 'posts.freelancer_id', '=', 'users.id')
                        ->orderBy('users.hourly_rate', 'asc')
                        ->select('posts.*'); // Ensure we only select posts columns
                } elseif ($sort == 'price_high') {
                    $query->join('users', 'posts.freelancer_id', '=', 'users.id')
                        ->orderBy('users.hourly_rate', 'desc')
                        ->select('posts.*'); // Ensure we only select posts columns
                } elseif ($sort == 'rating') {
                    $query->orderBy(DB::raw('IFNULL(average_rating, 0)'), 'desc');
                } elseif ($sort == 'newest') {
                    $query->orderBy('posts.created_at', 'desc');
                }
            } else {
                // Default sort
                $query->orderBy(DB::raw('IFNULL(average_rating, 0)'), 'desc');
            }
    $posts = $query->paginate(12);
    // dd(DB::getQueryLog());

        $notifications = auth()->user()->notifications; // Get all notifications
    
        // If profile is complete, show the dashboard without the modal
        return view('dashboard.customer-dashboard', [
            'user' => $user,
            'categories' => $categories, 
           'posts' => $posts,
           'notifications' => $notifications,
           
        ]);
    }
    

    public function showProfile()
{
    // Fetch the authenticated user's data
    $user = auth()->user();
    $notifications = $user->notifications; 
     // Get freelancers this customer has booked appointments with
      // Get freelancers this customer has completed appointments with
    $completedAppointments = Appointment::where('customer_id', $user->id)
        ->where('status', 'completed')  // Only consider completed appointments
        ->orderBy('date', 'desc')       // Most recent first
        ->get();
    
    // Extract unique freelancer IDs from completed appointments
    $freelancerIds = $completedAppointments->pluck('freelancer_id')->unique()->toArray();
    
    // Get the freelancer profiles
    $recentFreelancers = User::whereIn('id', $freelancerIds)
        ->where('role', 'freelancer')
        ->where('is_verified', true)
        ->with('categories')
        ->take(6)
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
    ->with('customer:id,firstname,lastname,profile_picture') // Load only customer name details
    ->get();
    $totalStars = $reviews->sum('rating'); // Sum of all ratings
    $totalReviews = $reviews->count();
   
    
   
    return view('PostSeeProfile', compact('freelancer', 'user','reviews', 'notifications','totalStars', 'totalReviews' ));
}


private function getStatusColor($status)
{
    return match ($status) {
        'pending'            => '#fbbf24', // Yellow
        'accepted'           => '#2563eb', // Blue
        'completed'          => '#10b981', // Green
        'Canceled'           => '#ef4444', // Red
        'declined'           => '#6b7280', // Gray
        'no_show_freelancer' => '#eab308', // Amber/Orange
        'no_show_customer'   => '#a21caf', // Purple
        default              => '#6b7280', // Default Gray
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
            'title' => $appointment->freelancer->firstname . ' ' . $appointment->freelancer->lastname . ' (' . ucfirst($appointment->status) . ')', 
            'start' => $appointment->date,
            'color' => $this->getStatusColor($appointment->status), 
        ];
    });

    return response()->json($events);
}

    public function getAppointmentDetails($id)
    {
          $appointment = Appointment::with([
        'freelancer',
        'post',
        'post.subServices' // Load subServices relationship
    ])
    ->where('id', $id)
    ->where('customer_id', auth()->id())
    ->firstOrFail();

    // Build the response with basic appointment details
    $response = [
        'id' => $appointment->id,
        'freelancer_id' => $appointment->freelancer_id,
        'freelancer_name' => $appointment->freelancer->firstname . ' ' . $appointment->freelancer->lastname,
        'date' => $appointment->date,
        'time' => $appointment->time,
        'address' => $appointment->address,
        'contact' => $appointment->contact ?? 'N/A',
        'notes' => $appointment->notes,
        'status' => ucfirst($appointment->status),
        'fee_status' => $appointment->fee_status,
        'commitment_fee' => $appointment->commitment_fee,
        'decline_reason' => $appointment->decline_reason,
        'final_payment_status' => $appointment->final_payment_status ?? 'pending',
        'total_amount' => $appointment->total_amount,
        'name' => $appointment->name
    ];

    // Add subservices as a simple concatenated string
    if ($appointment->post && $appointment->post->subServices && $appointment->post->subServices->count() > 0) {
        // Extract all subservice names
        $subServiceNames = $appointment->post->subServices->pluck('sub_service')->toArray();
        
        // Join them with commas
        $response['services_list'] = implode(', ', $subServiceNames);
    } else {
        // Fallback if no subservices
        $response['services_list'] = $appointment->name ?? 'Service';
    }

    return response()->json($response);
}

   public function cancelAppointment($id)
{
    $appointment = Appointment::where('id', $id)
        ->where('customer_id', auth()->id())
        ->firstOrFail();
    
    // Check if appointment is in a valid state to cancel
    if (!in_array($appointment->status, ['pending', 'accepted'])) {
        return response()->json([
            'error' => 'This appointment cannot be cancelled in its current state.'
        ], 400);
    }
    
    // Check if this is a late cancellation (less than 24 hours before appointment)
    $appointmentTime = \Carbon\Carbon::parse($appointment->date . ' ' . $appointment->time);
    $now = \Carbon\Carbon::now();
    $isLateCancellation = $now->diffInHours($appointmentTime) < 24;
    
    // Handle payment status based on cancellation timing
    if ($appointment->fee_status === 'paid' && $appointment->stripe_session_id) {
        if ($isLateCancellation) {
            // Late cancellation - commitment fee is forfeited
            $appointment->fee_status = 'forfeited';
            $appointment->decline_reason = 'late_cancellation';
            
            // Record platform revenue (10% commission)
            PlatformRevenue::create([
                'amount' => $appointment->commitment_fee * 0.1, // 10% platform fee
                'source' => 'late_cancellation_commission',
                'appointment_id' => $appointment->id,
                'user_id' => $appointment->freelancer_id,
                'date' => Carbon::now()->format('Y-m-d'),
                'notes' => 'Platform commission from late cancellation fee'
            ]);
            
            // Record freelancer earning (90% of commitment fee)
            FreelancerEarning::create([
                'freelancer_id' => $appointment->freelancer_id,
                'appointment_id' => $appointment->id,
                'amount' => $appointment->commitment_fee * 0.9, // 90% to freelancer
                'source' => 'late_cancellation_fee',
                'date' => Carbon::now()->format('Y-m-d'),
                'notes' => "Late cancellation fee for appointment #{$appointment->id}"
            ]);
            
        } else {
            // Early cancellation - try to refund the commitment fee
            try {
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                $session = \Stripe\Checkout\Session::retrieve($appointment->stripe_session_id);
                $paymentIntentId = $session->payment_intent;
                $refund = \Stripe\Refund::create(['payment_intent' => $paymentIntentId]);
                
                $appointment->fee_status = 'refunded';
                
            } catch (\Exception $e) {
                \Log::error('Stripe refund failed: ' . $e->getMessage());
                return response()->json([
                    'error' => 'Failed to refund commitment fee. Please contact support.'
                ], 500);
            }
        }
    }
    
    // Update appointment status
    $appointment->status = 'cancelled'; // Using consistent lowercase spelling
    $appointment->save();
    
    // Notify the freelancer
    $freelancer = $appointment->freelancer;
    $freelancer->notify(new AppointmentCanceled($appointment));
    
    // Return appropriate message
    $message = ($appointment->fee_status === 'forfeited') 
        ? 'Appointment cancelled. As this is a late cancellation, your commitment fee will not be refunded.'
        : 'Appointment cancelled successfully.';
        
    return response()->json(['message' => $message]);
}

    public function rescheduleAppointment(Request $request, $id)
{
     try {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required|string',
        ]);

        $appointment = Appointment::where('id', $id)
            ->where('customer_id', auth()->id())
            ->firstOrFail();
            
        // Store old date and time for notification
        $oldDate = $appointment->date;
        $oldTime = $appointment->time;

        $appointment->date = $validated['date'];
        $appointment->time = $validated['time'];
        $appointment->status = 'rescheduled'; // Update status to show it's been rescheduled
        $appointment->save();

        // Notify the freelancer about the reschedule
        $freelancer = $appointment->freelancer;
        if ($freelancer) {
            $freelancer->notify(new AppointmentRescheduled($appointment, $oldDate, $oldTime));
        }

        return response()->json(['message' => 'Appointment rescheduled successfully!']);
    } catch (\Exception $e) {
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
     $categories = Category::all();

    return view('dashboard.customer-dashboard', [
        'posts' => $posts,
        'searchTerm' => $searchTerm,
        'user' => $user,
        'notifications' => $notifications,
        'categories' => $categories,
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

public function getAvailability(Request $request, $freelancerId)
{ 
    try {
        // Get current appointment ID for rescheduling
        $currentAppointmentId = $request->input('current_appointment_id');
        
        // Log parameters for debugging
        \Log::info("Fetching availability", [
            'freelancer_id' => $freelancerId,
            'current_appointment_id' => $currentAppointmentId
        ]);
        
        // Fetch ALL availability for the given freelancer (no date filtering)
        $availabilities = FreelancerAvailability::where('freelancer_id', $freelancerId)
            ->orderBy('date')
            ->get();
            
        // Log what was found
        \Log::info("Availability records found", [
            'count' => $availabilities->count(),
            'dates' => $availabilities->pluck('date')->toArray()
        ]);
        
        // If no availabilities found, return empty array
        if ($availabilities->isEmpty()) {
            return response()->json([]);
        }

        // Build query for booked appointments
        $appointmentsQuery = Appointment::where('freelancer_id', $freelancerId)
            ->whereIn('date', $availabilities->pluck('date'))
            ->whereNotIn('status', ['canceled', 'declined']);
        
        // If rescheduling, exclude the current appointment
        if ($currentAppointmentId) {
            $appointmentsQuery->where('id', '!=', $currentAppointmentId);
        }
        
        $appointments = $appointmentsQuery->get();
        
        // Log appointments found
        \Log::info("Booked appointments found", [
            'count' => $appointments->count()
        ]);

        // Format the response
        $response = $availabilities->map(function ($availability) use ($appointments) {
            $bookedTimes = $appointments
                ->where('date', $availability->date)
                ->pluck('time')
                ->toArray();

            return [
                'date' => $availability->date,
                'start_time' => $availability->start_time,
                'end_time' => $availability->end_time,
                'booked_times' => $bookedTimes,
            ];
        });
        
        return response()->json($response);
    } catch (\Exception $e) {
        \Log::error('Error fetching availability: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        return response()->json(['error' => 'Failed to fetch availability'], 500);
    }
}


    
}