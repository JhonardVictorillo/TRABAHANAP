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
use App\Models\Violation;
use App\Models\ViolationAction;





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

            if ($priceRange == 'low') {
                $query->where('rate', '<', 1000);
            } elseif ($priceRange == 'medium') {
                $query->whereBetween('rate', [1000, 3000]);
            } elseif ($priceRange == 'high') {
                $query->where('rate', '>', 3000);
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
                    $query->orderBy('rate', 'asc');
                } elseif ($sort == 'price_high') {
                    $query->orderBy('rate', 'desc');
                 // Ensure we only select posts columns
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

        
    // Fetch popular categories based on actual bookings and ratings
   $mostBookedCategories = DB::table('categories')
    ->select('categories.id', 'categories.name', 'categories.created_at', 'categories.updated_at', 'categories.image_path', DB::raw('COUNT(appointments.id) as booking_count'))
    ->join('category_user', 'categories.id', '=', 'category_user.category_id')
    ->join('posts', 'category_user.user_id', '=', 'posts.freelancer_id')
    ->join('appointments', 'posts.id', '=', 'appointments.post_id')
    ->whereIn('appointments.status', ['completed', 'paid'])
    ->groupBy('categories.id', 'categories.name', 'categories.created_at', 'categories.updated_at', 'categories.image_path') // Added image_path
    ->orderBy('booking_count', 'desc')
    ->limit(4)
    ->get();

// Convert to collection and add default rating
$popularCategories = collect($mostBookedCategories)->map(function($category) {
    // Set a default average rating of 0
    $category->average_rating = 0;
    return $category;
});        
    
        // If profile is complete, show the dashboard without the modal
        return view('dashboard.customer-dashboard', [
            'user' => $user,
            'categories' => $categories, 
           'posts' => $posts,
           'notifications' => $notifications,
             'popularCategories' => $popularCategories,
           
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

public function showFreelancerProfile($id, $postId = null)
{
    \Log::debug("showFreelancerProfile called with ID: $id, postId: $postId");
     $freelancer = User::where('id', $id)
        ->where('is_verified', true)  // Only allow verified freelancers
        ->with(['posts.pictures'])
        ->firstOrFail();

    $post = null;
    if ($postId) {
        $post = Post::findOrFail($postId);
        \Log::debug("Post found by ID", [
            'post_id' => $post->id,
            'freelancer_id' => $post->freelancer_id,
            'location_restriction' => $post->location_restriction
        ]);
    } elseif ($freelancer->posts->isNotEmpty()) {
        $post = $freelancer->posts->first();
        \Log::debug("Using first post from freelancer", [
            'post_id' => $post->id,
            'location_restriction' => $post->location_restriction
        ]);
    }

    $user = Auth::user(); 
    $notifications = $user->notifications;
         // Fetch only reviews where a rating and review exist
    $reviews = Appointment::where('freelancer_id', $id)
    ->whereNotNull('rating') // Ensure there's a rating
    ->with('customer:id,firstname,lastname,profile_picture') // Load only customer name details
    ->get();
    $totalStars = $reviews->sum('rating'); // Sum of all ratings
    $totalReviews = $reviews->count();

      $commitmentFee = 30; // Fixed ₱30

    return view('PostSeeProfile', compact('freelancer', 'post', 'user','reviews', 'notifications','totalStars', 'totalReviews', 'commitmentFee'));
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
        'name' => $appointment->name,
        'rate' => $appointment->post->rate ?? 0,
        'rate_type' => $appointment->post->rate_type ?? '',
          'duration' => $appointment->duration ?? ($appointment->post ? $appointment->post->service_duration : 60),
        'buffer_time' => $appointment->post ? $appointment->post->buffer_time : 0,
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
    try {
        // Find the appointment
        $appointment = Appointment::where('id', $id)
            ->where('customer_id', auth()->id())
            ->firstOrFail();
        
        // Check if appointment can be cancelled
        if (!in_array($appointment->status, ['pending', 'accepted'])) {
            return response()->json([
                'error' => 'This appointment cannot be cancelled in its current state.'
            ], 400);
        }
        
        // Check if this is a late cancellation (less than 24 hours before)
        $appointmentTime = \Carbon\Carbon::parse($appointment->date . ' ' . $appointment->time);
        $now = \Carbon\Carbon::now();
        $isLateCancellation = $now->diffInHours($appointmentTime) < 24;
        
        // Start transaction
        DB::beginTransaction();
        
        // Set status based on timing
        if ($appointment->fee_status === 'paid') {
            if ($isLateCancellation) {
                // Late cancellation - fee is forfeited
                $appointment->fee_status = 'forfeited';
                $message = 'Appointment cancelled. As this is a late cancellation, your commitment fee will not be refunded.';
                
                // Update platform revenue record if it exists
                $heldFee = PlatformRevenue::where('appointment_id', $appointment->id)
                    ->where('source', 'commitment_fee_held')
                    ->first();
                
                if ($heldFee) {
                    $heldFee->status = 'forfeited';
                    $heldFee->source = 'commitment_fee_forfeited';
                    $heldFee->save();
                }
                
                // Record the late cancellation as a violation
                $user = auth()->user();
                
                // Create a violation record in the violations table
                $violation = new \App\Models\Violation();
                $violation->user_id = auth()->id();
                $violation->user_role = 'customer';
                $violation->violation_type = 'late_cancellation';
                $violation->appointment_id = $appointment->id;
                $violation->notes = 'Customer cancelled less than 24 hours before appointment time';
                $violation->status = 'active'; // Set as active violation
                $violation->created_at = now();
                $violation->save();
                
                // Update the user's violation counters
                $user->increment('violation_count');
                $user->increment('late_cancel_count');
                $user->last_violation_at = now();
                $user->save();
                
                // Check for violation threshold and apply penalties if needed
                $violationCount = Violation::where('user_id', auth()->id())
                    ->where('violation_type', 'late_cancellation')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->count();
                
                // If multiple violations within 30 days, create violation action
                if ($violationCount >= 3) {
                    $violationAction = new \App\Models\ViolationAction();
                    $violationAction->violation_id = $violation->id;
                    $violationAction->action_type = 'warning';
                    $violationAction->notes = 'Automatic warning for repeated late cancellations';
                    $violationAction->action_data = json_encode([
                        'violation_count' => $violationCount,
                        'user_id' => auth()->id(),
                        'timestamp' => now()
                    ]);
                    $violationAction->save();
                    
                    // If 5 or more violations, trigger suspension
                    if ($violationCount >= 5) {
                        // Get suspension settings from violation settings
                        $settings = \App\Models\ViolationSetting::where('role', 'customer')->first();
                        $suspensionDays = $settings ? $settings->suspension_days : 7; // Default 7 days
                        
                        // Set user suspension
                        $user->is_suspended = true;
                        $user->suspension_end = now()->addDays($suspensionDays);
                        $user->suspension_reason = 'Multiple late cancellations';
                        $user->save();
                        
                        // Record suspension action
                        $suspensionAction = new \App\Models\ViolationAction();
                        $suspensionAction->violation_id = $violation->id;
                        $suspensionAction->action_type = 'suspension';
                        $suspensionAction->notes = "Automatic {$suspensionDays}-day suspension for repeated late cancellations";
                        $suspensionAction->action_data = json_encode([
                            'suspension_days' => $suspensionDays,
                            'violation_count' => $violationCount,
                            'end_date' => now()->addDays($suspensionDays)
                        ]);
                        $suspensionAction->save();
                    }
                }
            } else {
                // For immediate refunds using the stripe_session_id
                try {
                    if (!empty($appointment->stripe_session_id)) {
                        // Set Stripe API key
                        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                        
                        // First retrieve the session to get the payment intent
                        $session = \Stripe\Checkout\Session::retrieve($appointment->stripe_session_id);
                        
                        // Check if the session has a payment intent
                        if (!empty($session->payment_intent)) {
                            // Create a refund using the payment intent
                            $refund = \Stripe\Refund::create([
                                'payment_intent' => $session->payment_intent
                            ]);
                            
                            $appointment->fee_status = 'refunded';
                            $message = 'Appointment cancelled. Your commitment fee has been refunded.';
                            
                            // Log the successful refund
                            \Log::info('Refund processed successfully', [
                                'appointment_id' => $appointment->id,
                                'refund_id' => $refund->id,
                                'session_id' => $appointment->stripe_session_id
                            ]);
                            
                            // Update platform revenue record if it exists
                            $heldFee = PlatformRevenue::where('appointment_id', $appointment->id)
                                ->where('source', 'commitment_fee_held')
                                ->first();
                                
                            if ($heldFee) {
                                $heldFee->status = 'refunded';
                                $heldFee->source = 'commitment_fee_refunded';
                                $heldFee->save();
                            }
                        } else {
                            throw new \Exception('No payment intent found in session');
                        }
                    } else {
                        throw new \Exception('No Stripe session ID available');
                    }
                } catch (\Exception $e) {
                    // If automatic refund fails, mark for manual refund
                    \Log::warning('Automatic refund failed: ' . $e->getMessage(), [
                        'appointment_id' => $appointment->id,
                        'stripe_session_id' => $appointment->stripe_session_id ?? 'not set'
                    ]);
                    
                    $appointment->fee_status = 'refund_pending';
                    $message = 'Appointment cancelled. Your refund is being processed and will be reflected within 3-5 business days.';
                }
            }
        } else {
            // No payment to handle
            $message = 'Appointment cancelled successfully.';
        }
        
        // Update appointment status
        $appointment->status = 'cancelled';
        $appointment->save();
        
        // Notify the freelancer
        try {
            $freelancer = $appointment->freelancer;
            if ($freelancer) {
                $freelancer->notify(new AppointmentCanceled($appointment));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
            // Continue even if notification fails
        }
        
        DB::commit();
        return response()->json(['message' => $message]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error cancelling appointment: ' . $e->getMessage(), [
            'appointment_id' => $id,
            'user_id' => auth()->id(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString() // Add full stack trace
        ]);
        return response()->json(['error' => 'Failed to cancel appointment. Please try again.'], 500);
    }
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
   
    $user = Auth::user();
    $searchTerm = $request->query('q'); // Get the search term
    $categories = Category::all(); // Fetch all categories

    $posts = Post::where('status', 'approved')
        ->whereHas('freelancer', function ($query) {
            $query->where('is_verified', true);
        })
        ->with(['freelancer', 'freelancer.categories']);

    if (!empty($searchTerm)) {
        $posts = $posts->where(function ($query) use ($searchTerm) {
            $query->where('description', 'LIKE', '%' . $searchTerm . '%')
                ->orWhereHas('freelancer', function ($subQuery) use ($searchTerm) {
                    $subQuery->where('firstname', 'LIKE', '%' . $searchTerm . '%')
                             ->orWhere('lastname', 'LIKE', '%' . $searchTerm . '%')
                             ->orWhere('barangay', 'LIKE', '%' . $searchTerm . '%') // <-- Add this line
                             ->orWhere('city', 'LIKE', '%' . $searchTerm . '%')
                             ->orWhere('province', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orWhereHas('freelancer.categories', function ($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
                });
        });
    }

    $posts = $posts->with(['freelancer', 'freelancer.categories'])->get();
    $notifications = $user->unreadNotifications;

    // If your view expects popularCategories, fetch and pass it as well
    $popularCategories = Category::orderBy('name')->take(4)->get();

    return view('dashboard.customer-dashboard', [
        'posts' => $posts,
        'searchTerm' => $searchTerm,
        'user' => $user,
        'notifications' => $notifications,
        'categories' => $categories,
        'popularCategories' => $popularCategories,
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
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $today = now()->toDateString();
        
        // Log parameters for debugging
        \Log::info("Fetching availability", [
            'freelancer_id' => $freelancerId,
            'year' => $year,
            'month' => $month,
            'current_appointment_id' => $currentAppointmentId
        ]);
        
        // Get the appointment date for rescheduling if applicable
        $appointmentDate = null;
        if ($currentAppointmentId) {
            $currentAppointment = Appointment::find($currentAppointmentId);
            $appointmentDate = $currentAppointment ? $currentAppointment->date : null;
        }

        // Get the freelancer's availabilities for the specified month
        $availabilities = FreelancerAvailability::where('freelancer_id', $freelancerId)
            ->where(function ($query) use ($year, $month, $today, $appointmentDate) {
                $query->whereYear('date', $year)
                    ->whereMonth('date', $month);
                
                // Always include the appointment date if rescheduling
                if ($appointmentDate) {
                    $query->orWhereDate('date', $appointmentDate);
                }
            })
            ->where(function($query) use ($today) {
                // Ensure we only include today or future dates
                $query->whereDate('date', '>=', $today);
            })
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

        // Format the response with booking details including duration
        $response = $availabilities->map(function ($availability) use ($appointments) {
            // Get all bookings for this date with their complete details
            $bookings = $appointments->where('date', $availability->date)
                ->map(function ($appointment) {
                    // Get the post for this appointment to retrieve duration and buffer time
                    $post = $appointment->post;
                    
                    // Get the service duration and buffer time
                    $duration = $appointment->duration ?? ($post ? $post->getDefaultDuration() : 60);
                    $bufferTime = $post ? $post->getBufferTime() : 0;
                    
                    // Parse appointment time (HH:MM format)
                    $timeParts = explode(':', $appointment->time);
                    $startHour = (int)$timeParts[0];
                    $startMinute = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
                    
                    // Calculate end time in minutes
                    $startTimeInMinutes = ($startHour * 60) + $startMinute;
                    $endTimeInMinutes = $startTimeInMinutes + $duration;
                    
                    // Calculate buffer end time
                    $bufferEndTimeInMinutes = $endTimeInMinutes + $bufferTime;
                    
                    // Format the end times back to hours and minutes
                    $endHour = floor($endTimeInMinutes / 60);
                    $endMinute = $endTimeInMinutes % 60;
                    
                    $bufferEndHour = floor($bufferEndTimeInMinutes / 60);
                    $bufferEndMinute = $bufferEndTimeInMinutes % 60;
                    
                    // Return booking details with timing information
                    return [
                        'appointment_id' => $appointment->id,
                        'time' => $appointment->time,
                        'duration' => $duration,
                        'buffer_time' => $bufferTime,
                        'end_time' => sprintf("%02d:%02d", $endHour, $endMinute),
                        'buffer_end_time' => sprintf("%02d:%02d", $bufferEndHour, $bufferEndMinute),
                        'total_minutes' => $duration + $bufferTime
                    ];
                })->values()->toArray();
            
            // Also keep the simple array of booked times for backward compatibility
            $bookedTimes = collect($bookings)->pluck('time')->toArray();
            
            return [
                'date' => $availability->date,
                'start_time' => $availability->start_time,
                'end_time' => $availability->end_time,
                'booked_times' => $bookedTimes,
                'bookings' => $bookings
            ];
        });
        
        return response()->json($response);
    } catch (\Exception $e) {
        \Log::error('Error fetching availability: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Failed to fetch availability'], 500);
    }
}
    
}