<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Freelancer Profile</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
     <link rel ="stylesheet" href="{{asset ('css/customerHeader.css')}}" />
    <link rel ="stylesheet" href="{{asset ('css/PostSeeProfile.css')}}" />
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Pacifico&family=Inter:wght@400;500;600&family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
      rel="stylesheet"
    />
    
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
            primary: "#2563eb", // Changed from #118f39 to royal blue
          secondary: "#64748B",
            },
            borderRadius: {
              none: "0px",
              sm: "4px",
              DEFAULT: "8px",
              md: "12px",
              lg: "16px",
              xl: "20px",
              "2xl": "24px",
              "3xl": "32px",
              full: "9999px",
              button: "8px",
            },
            fontFamily: {
              inter: ["Inter", "sans-serif"],
              poppins: ["Poppins", "sans-serif"],
            },
          },
        },
      };
    </script>
    <style>
      /* :where([class^="ri-"])::before { content: "\f3c2"; } */
      .search-input:focus {
      outline: none;
      box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
      }
      .service-card:hover {
      transform: translateY(-2px);
      transition: all 0.2s ease-in-out;
      }
    </style>
  </head>
  <body class="bg-[#F8F9FA] font-inter">
   @include('customer.customerHeader')
      @include('successMessage')
   <main class="max-w-7xl mx-auto px-4 md:px-8 py-6 md:py-8">
  <div class="mb-4">
    <a href="{{ route('customer.dashboard') }}">
      <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-primary bg-white border border-primary hover:bg-primary hover:text-white transition rounded-lg shadow-sm">
        <i class="ri-arrow-left-line text-lg"></i>
      </button>
    </a>
  </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-8">
        <!-- Left Section -->
       <div class="lg:col-span-2">
         <div class="bg-white rounded-lg p-4 md:p-8 mb-8">
                <!-- Profile Section -->
                 <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 mb-6">
                  <img
                    src="{{ $freelancer->profile_picture ? asset('storage/' . $freelancer->profile_picture) : asset('images/defaultprofile.jpg') }}"
                    alt="{{ $freelancer->firstname }}"
                    class="w-32 h-32 sm:w-40 sm:h-40 rounded-full object-cover mx-auto sm:mx-0"
                  />
                     <div class="flex-1 text-center sm:text-left">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h2 class="text-2xl font-semibold mb-1">{{ $freelancer->firstname }} {{ $freelancer->lastname }}</h2>
                                @if($freelancer->categories->isEmpty())
                                    <p class="text-gray-600">No categories selected</p>
                                @else
                                    @foreach($freelancer->categories as $category)
                                        <p class="text-gray-600">{{ $category->name }}</p>
                                    @endforeach
                                @endif
                            </div>
                            <div class="flex items-center gap-1">
                            @if($freelancer->is_verified)
                                <div class="w-5 h-5 flex items-center justify-center text-blue-500">
                                    <i class="ri-verified-badge-fill"></i>
                                </div>
                                <span class="text-sm font-medium text-blue-500">Verified</span>
                            @else
                                <span class="text-sm font-medium text-gray-400">Not Verified</span>
                            @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-6 mb-4">
                            <div class="flex items-center gap-1">
                                <div class="w-4 h-4 flex items-center justify-center text-yellow-400">
                                    <i class="ri-star-fill"></i>
                                </div>
                                <span class="font-medium">{{ $totalStars }}</span>
                                <span class="text-gray-400">({{ $totalReviews }} reviews)</span>
                            </div>
                            <div class="flex items-center gap-1 text-gray-600">
                                <div class="w-4 h-4 flex items-center justify-center">
                                    <i class="ri-map-pin-line"></i>
                                </div>
                                <span>{{ $freelancer->province }}, {{ $freelancer->city }}, {{$freelancer->barangay}}, {{ $freelancer->zipcode }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <button class="flex-1 py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 !rounded-button">
                                Message
                            </button>
                            <button class="flex-1 py-2 text-sm font-medium text-primary bg-blue-50 hover:bg-blue-100 !rounded-button">
                                Contact
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Stats Section -->
                 <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-lg mb-6">
                    <div class="text-center">
                           <div class="text-2xl font-semibold mb-1">
                                {{ $freelancer->jobSuccessRate() }}%
                            </div>
                        <p class="text-sm text-gray-600">Job Success</p>
                    </div>
                    <div class="text-center">
                         <div class="text-2xl font-semibold mb-1">
                                {{ $freelancer->posts->count() }}
                            </div>
                        <p class="text-sm text-gray-600">Projects</p>
                    </div>
                    <div class="text-center">
                       <div class="text-2xl font-semibold mb-1">
                            {{ $freelancer->appointments->count() }}
                        </div>
                        <p class="text-sm text-gray-600">Total Appointments</p>
                    </div>
                  <div class="text-center">
                    <div class="text-2xl font-semibold mb-1">
                        {{ $freelancer->experience_years ?? 'N/A' }} yrs
                    </div>
                    <p class="text-sm text-gray-600">Experience</p>
                </div>
                </div>
               
            <p class="text-gray-600 leading-relaxed mb-8">
                {{ $freelancer->bio ?? 'No bio provided.' }}
            </p>
                <!-- Portfolio Section -->
                <div class="border-t pt-8">
                    <h3 class="text-lg font-semibold mb-6">Recent works</h3>
                 <div class="max-h-96 overflow-y-auto pr-2">
                    <div class="grid grid-cols-2 gap-6">
                        @forelse ($freelancer->posts as $portfolioPost)
                            @forelse ($portfolioPost->pictures as $picture)
                                <div class="bg-white rounded-lg overflow-hidden shadow-sm">
                                    <img
                                        src="{{ asset('storage/' . $picture->image_path) }}"
                                        alt="Portfolio"
                                        class="w-full h-48 object-cover"
                                    />
                                </div>
                            @empty
                                <p>No images available for this post.</p>
                            @endforelse
                        @empty
                            <p>No recent works available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
         </div>
            <!-- Client Reviews Section -->
            <div class="bg-white rounded-lg p-8">
                <h3 class="text-lg font-semibold mb-6">Client Reviews</h3>
               <div class="space-y-6 max-h-80 overflow-y-auto pr-2 border border-gray-100 rounded-lg bg-gray-50">
                    @if ($reviews->isNotEmpty())
                        @foreach ($reviews as $review)
                            <div class="pb-6 border-b">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-4">
                                        <img
                                            src="{{ $review->customer && $review->customer->profile_picture ? asset('storage/' . $review->customer->profile_picture) : asset('images/defaultprofile.jpg') }}"
                                            alt="Client"
                                            class="w-10 h-10 rounded-full object-cover"
                                        />
                                        <div>
                                            <h4 class="font-medium">{{ $review->customer->firstname ?? 'Unknown' }} {{ $review->customer->lastname ?? '' }}</h4>
                                            <p class="text-sm text-gray-600">{{ $review->customer->job_title ?? 'Client' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 text-yellow-400">
                                        @for ($i = 0; $i < $review->rating; $i++)
                                            <i class="ri-star-fill"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-600">{{ $review->review }}</p>
                            </div>
                        @endforeach
                    @else
                        <p>No reviews yet.</p>
                    @endif
                </div>
            </div>
        </div>
        <!-- Right Section -->
        <div class="col-span-1 order-first lg:order-none mb-6 lg:mb-0">
          <div class="bg-white rounded-lg p-6 shadow-lg sticky top-20">
                <h3 class="text-lg font-semibold mb-6">Book Appointment Here</h3>
                <!-- Add this below "Book Appointment Here" heading in the right column -->
                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                <div class="flex flex-row items-center justify-between gap-3">
                    <!-- Service Duration -->
                    <div class="flex items-center gap-2 flex-1">
                        <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center bg-primary/10 rounded-full">
                            <i class="ri-time-line text-primary"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium">Duration</h4>
                            <p class="text-xs text-gray-600">
                                @if(floor($post->getDefaultDuration() / 60) > 0)
                                    {{ floor($post->getDefaultDuration() / 60) }} hour(s)
                                @endif
                                @if($post->getDefaultDuration() % 60 > 0)
                                    {{ $post->getDefaultDuration() % 60 }} minutes
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <!-- Service Rate -->
                    <div class="flex items-center gap-2 flex-1">
                        <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center bg-blue-50 rounded-full">
                            <i class="ri-money-dollar-circle-line text-blue-500"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium">Rate</h4>
                            <p class="text-xs text-gray-600">₱{{ $post->rate }} / {{ $post->rate_type }}</p>
                        </div>
                    </div>
                </div>
            </div>
                <p class="text-sm text-gray-600 mb-6">
                    Book a consultation to discuss your digital marketing needs and goals.
                </p>
                  @if(auth()->check() && (auth()->user()->is_suspended || auth()->user()->is_banned))
                        <button disabled
                            class="w-full py-2 text-sm font-medium text-white bg-gray-400 cursor-not-allowed !rounded-button"
                        >
                            @if(auth()->user()->is_suspended)
                                Account Suspended
                            @else
                                Account Banned
                            @endif
                        </button>
                        <p class="text-xs text-red-600 mt-2">
                            @if(auth()->user()->is_suspended)
                                Your account is suspended until {{ auth()->user()->suspension_end->format('M d, Y') }}
                            @else
                                Your account has been banned. Please contact support.
                            @endif
                        </p>
                   @elseif(auth()->check() && auth()->user()->is_restricted && (!auth()->user()->restriction_end || now()->lessThan(auth()->user()->restriction_end)))
                    <button disabled
                        class="w-full py-2 text-sm font-medium text-white bg-yellow-400 cursor-not-allowed !rounded-button"
                    >
                        Booking Restricted
                    </button>
                    <p class="text-xs text-yellow-600 mt-2">
                        Your account is restricted from booking appointments.
                        @if(auth()->user()->restriction_end)
                            Restriction ends on {{ auth()->user()->restriction_end->format('M d, Y') }}.
                        @endif
                        Please contact support for more information.
                    </p>
                @else
                    <button
                        id="bookButton"
                        class="w-full py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 !rounded-button"
                    >
                        Book Appointment
                    </button>
                @endif
                
            </div>
           
    </div>
    
   <!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 px-4">
  <div class="bg-white rounded-lg w-full max-w-lg mx-auto relative flex flex-col" style="max-height:90vh;">
    <!-- Modal Header -->
    <div class="p-4 sm:p-6 border-b border-gray-200 flex justify-between items-center">
      <h3 class="text-xl font-semibold">Book an Appointment</h3>
      <button id="closeModal" class="text-gray-400 hover:text-gray-600">
        <div class="w-6 h-6 flex items-center justify-center">
          <i class="ri-close-line"></i>
        </div>
      </button>
    </div>
    
    <!-- Service Info -->
    <div class="px-4 sm:px-6 pt-4 bg-gray-50">
      <div class="flex items-center gap-2 mb-1">
        <i class="ri-time-line text-gray-500"></i>
        <span class="text-sm text-gray-700">
          Service Duration: 
          <span class="font-medium">
            @if(floor($post->getDefaultDuration() / 60) > 0)
              {{ floor($post->getDefaultDuration() / 60) }} hour(s)
            @endif
            @if($post->getDefaultDuration() % 60 > 0)
              {{ $post->getDefaultDuration() % 60 }} minutes
            @endif
          </span>
        </span>
      </div>
      @if($post->getBufferTime() > 0)
      <div class="flex items-center gap-2 pb-4">
        <i class="ri-timer-line text-gray-500"></i>
        <span class="text-sm text-gray-700">
          Buffer Time: <span class="font-medium">{{ $post->getBufferTime() }} minutes</span>
        </span>
      </div>
      @endif
    </div>
    
    <!-- Modal Body (Scrollable) -->
    <div class="flex-1 overflow-y-auto p-4 sm:p-6 pt-4">
      <form id="bookingForm" method="POST" action="{{ route('pay.commitment') }}" class="flex flex-col h-full">
        @csrf
        <input type="hidden" id="locationRestriction" value="{{ $post->location_restriction ?? 'open' }}">
        <input type="hidden" name="freelancer_id" value="{{ $freelancer->id }}">
        <input type="hidden" name="post_id" value="{{ $post->id }}">
        <input type="hidden" id="selectedDate" name="date" required>
        <input type="hidden" id="selectedTime" name="time" required>
        <input type="hidden" name="notes" id="notesInput">
        <input type="hidden" name="commitment_fee" value="{{ $commitment_fee ?? 100 }}">
        <input type="hidden" id="serviceDuration" value="{{ $post->getDefaultDuration() }}">
        <input type="hidden" id="bufferTime" value="{{ $post->getBufferTime() }}">
        <input type="hidden" id="selectedDuration" name="duration" value="">
        
        <!-- Calendar Section -->
        <div class="mb-6">
          <div class="flex items-center justify-between mb-4">
            <button type="button" id="prevMonthButton" class="text-gray-400 hover:text-gray-600">
              <div class="w-6 h-6 flex items-center justify-center">
                <i class="ri-arrow-left-s-line"></i>
              </div>
            </button>
            <h4 id="monthTitle" class="text-base font-medium"></h4>
            <button type="button" id="nextMonthButton" class="text-gray-400 hover:text-gray-600">
              <div class="w-6 h-6 flex items-center justify-center">
                <i class="ri-arrow-right-s-line"></i>
              </div>
            </button>
          </div>
          <div class="grid grid-cols-7 gap-1 text-center mb-2">
            <div class="text-sm text-gray-600">Sun</div>
            <div class="text-sm text-gray-600">Mon</div>
            <div class="text-sm text-gray-600">Tue</div>
            <div class="text-sm text-gray-600">Wed</div>
            <div class="text-sm text-gray-600">Thu</div>
            <div class="text-sm text-gray-600">Fri</div>
            <div class="text-sm text-gray-600">Sat</div>
          </div>
          <div id="calendarGrid" class="grid grid-cols-7 gap-1 text-center"></div>
        </div>

        <!-- Time Slots Section -->
        <div class="mb-6">
          <h5 class="text-sm font-medium mb-4">Available Time Slots</h5>
          <div id="timeSlots" class="grid grid-cols-2 gap-2"></div>
        </div>

        <!-- Notes Section -->
        <div class="mb-6">
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes to Freelancer (Optional)</label>
        <textarea id="notes"  rows="3"  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary resize-none text-sm" 
            placeholder="Add any details that might help the freelancer prepare for your appointment.."
         ></textarea>
        <p class="mt-1.5 text-xs text-gray-500">This information will be shared with the freelancer when they receive your booking request.</p>
        </div>

        <!-- Commitment Fee Section -->
        <div class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
          <div class="flex items-center gap-2 mb-1">
            <i class="ri-information-line text-yellow-400 text-lg"></i>
            <span class="font-semibold text-yellow-700">Commitment Fee Required</span>
          </div>
          <p class="text-sm text-yellow-700">
            To book this appointment, a non-refundable commitment fee of 
            <span class="font-bold text-yellow-900">₱{{ number_format($commitmentFee, 2) }}</span>
            is required. This fee will not be refunded if you cancel after the freelancer accepts your booking.
          </p>
        </div>
      </div>

      <!-- Sticky Submit Button (Fixed Footer) -->
      <div class="p-4 sm:p-6 border-t border-gray-200 bg-white">
        <p id="validationMsg" class="text-red-500 text-sm mb-3 text-center">
          Please select both a date and time to proceed
        </p>
        <button type="submit" id="submitButton" class="w-full py-3 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded opacity-50 cursor-not-allowed">
          <span class="btn-text">Proceed to Payment</span>
          <span class="btn-spinner" style="display:none;">
            <i class="ri-loader-4-line animate-spin"></i>
          </span>
        </button>
      </div>
    </form>
  </div>
</div>
</main>
 
 
      <!-- Success message -->
    @include('successMessage')
      
    @include('customer.footer')


    <script>

    document.addEventListener("DOMContentLoaded", function () {
    // Existing variables
    const bookButton = document.getElementById("bookButton");
    const bookingModal = document.getElementById("bookingModal");
    const closeModal = document.getElementById("closeModal");
    const calendarGrid = document.getElementById("calendarGrid");
    const monthTitle = document.getElementById("monthTitle");
    const nextMonthButton = document.getElementById("nextMonthButton");
    const prevMonthButton = document.getElementById("prevMonthButton");
    const selectedDateInput = document.getElementById("selectedDate");
    const selectedTimeInput = document.getElementById("selectedTime");
    const validationMsg = document.getElementById("validationMsg");
    const submitButton = document.getElementById("submitButton");
    
    // FIX: Use ID selector for time slots container instead of class selector
    const timeButtonsContainer = document.getElementById("timeSlots");

    const months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December",
    ];

    let currentDate = new Date();
    let freelancerId = "{{ $freelancer->id }}";

    // Function to fetch availability
    function fetchAvailability(year, month) {
        const serviceDuration = document.getElementById('serviceDuration').value;
        const bufferTime = document.getElementById('bufferTime').value;
    
        console.log(`Fetching availability for year=${year}, month=${month+1}, duration=${serviceDuration}, buffer=${bufferTime}`);
        return fetch(`/freelancer/${freelancerId}/availability?year=${year}&month=${month + 1}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("Fetched availability data:", data);
                return data;
            })
            .catch(error => {
                console.error("Error fetching availability:", error);
                alert("Could not load freelancer's availability. Please try again later.");
                return [];
            });
    }

    // Function to update the calendar
    async function updateCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const firstDay = new Date(year, month, 1).getDay();
        const lastDate = new Date(year, month + 1, 0).getDate();
        const today = new Date();

        // Check if current month is in the past
        const isPastMonth = (year < today.getFullYear()) || 
                          (year === today.getFullYear() && month < today.getMonth());
                          
        // Disable previous month button if we're at current month
        prevMonthButton.disabled = (year === today.getFullYear() && month === today.getMonth());
        prevMonthButton.classList.toggle("opacity-50", prevMonthButton.disabled);
        
        monthTitle.textContent = `${months[month]} ${year}`;
        calendarGrid.innerHTML = "";

        // Fetch availability for the current month
        const availability = await fetchAvailability(year, month);

        // Clear time slots for a new month selection
        // FIX: Use ID selector instead of class selector
        document.getElementById("timeSlots").innerHTML = `
            <p class="col-span-3 text-sm text-gray-600 py-4 text-center">
                Please select a date to view available time slots.
            </p>`;

        // If this is a past month, disable all dates
        if (isPastMonth) {
            // Add empty cells for days before the first day of the month
            for (let i = 0; i < firstDay; i++) {
                calendarGrid.innerHTML += `<div class="text-sm text-gray-300"></div>`;
            }
            
            // Add cells for each day of the month (all disabled)
            for (let day = 1; day <= lastDate; day++) {
                const date = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
                
                calendarGrid.innerHTML += `
                    <div class="text-sm py-2 bg-gray-200 text-gray-400 cursor-not-allowed opacity-70" 
                        data-date="${date}" disabled>
                        ${day}
                    </div>`;
            }
            
            // Clear time slots
            document.getElementById("timeSlots").innerHTML = `
                <p class="col-span-3 text-sm text-gray-600 py-4 text-center">
                    Appointments cannot be scheduled for past months.
                    Please select a date in the current or future months.
                </p>`;
            
            return;
        }

        // Add empty cells for days before the first day of the month
        for (let i = 0; i < firstDay; i++) {
            calendarGrid.innerHTML += `<div class="text-sm text-gray-300"></div>`;
        }

        // Add cells for each day of the month
        for (let day = 1; day <= lastDate; day++) {
            const date = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
            const isAvailable = availability.some(avail => avail.date === date);
            
            // Check if this date is in the past (for current month)
            const isPastDate = (year === today.getFullYear() && 
                            month === today.getMonth() && 
                            day < today.getDate());
            
            // Disable past dates even if they're available
            const isSelectable = isAvailable && !isPastDate;

            calendarGrid.innerHTML += `
                <div class="text-sm py-2 ${
                    isSelectable
                        ? "bg-blue-600 text-white rounded-full cursor-pointer"
                        : "bg-gray-200 text-gray-400 cursor-not-allowed"
                }" data-date="${date}" ${isSelectable ? "" : "disabled"}>
                    ${day}
                </div>`;
        }

        // Add click event to available days only
        document.querySelectorAll("#calendarGrid div[data-date]:not([disabled])").forEach((day) => {
            day.addEventListener("click", function () {
                document
                    .querySelectorAll("#calendarGrid div")
                    .forEach((d) => d.classList.remove("bg-blue-600", "text-white"));
                this.classList.add("bg-blue-600", "text-white");
                selectedDateInput.value = this.getAttribute("data-date");

                // Update time slots for the selected date
                updateTimeSlots(this.getAttribute("data-date"), availability);
                checkFormValidity();
            });
        });
    }

    function updateTimeSlots(selectedDate, availability) {
    // FIX: Use ID selector for time slots container
    const timeButtonsContainer = document.getElementById("timeSlots");
    timeButtonsContainer.innerHTML = ""; // Clear previous time slots
    
    if (!availability || availability.length === 0) {
        timeButtonsContainer.innerHTML = `
            <p class="col-span-2 text-sm text-gray-600 py-4 text-center">
                No schedules have been set for this month.
                Please try selecting a different month.
            </p>`;
        return;
    }
    
    // Find the availability for the selected date
    const availableDay = availability.find(avail => avail.date === selectedDate);
    
    if (availableDay) {
        // Parse start and end times from 12-hour format
        let startHour, endHour;
        
        // Parse start time
        const startMatch = availableDay.start_time.match(/(\d+):(\d+)\s*(AM|PM)/i);
        if (startMatch) {
            startHour = parseInt(startMatch[1]);
            if (startMatch[3].toUpperCase() === 'PM' && startHour !== 12) {
                startHour += 12;
            } else if (startMatch[3].toUpperCase() === 'AM' && startHour === 12) {
                startHour = 0;
            }
        } else {
            startHour = 9; // Default
        }
        
        // Parse end time
        const endMatch = availableDay.end_time.match(/(\d+):(\d+)\s*(AM|PM)/i);
        if (endMatch) {
            endHour = parseInt(endMatch[1]);
            if (endMatch[3].toUpperCase() === 'PM' && endHour !== 12) {
                endHour += 12;
            } else if (endMatch[3].toUpperCase() === 'AM' && endHour === 12) {
                endHour = 0;
            }
        } else {
            endHour = 17; // Default
        }
        
        const bookedTimes = availableDay.booked_times || []; // Get booked times
        
        // Get service duration and buffer time
        const serviceDuration = parseInt(document.getElementById('serviceDuration').value || 60);
        const bufferTime = parseInt(document.getElementById('bufferTime').value || 0);
        const totalSlotTime = serviceDuration + bufferTime; // Total time needed per slot
        
        console.log(`Service duration: ${serviceDuration} mins, Buffer: ${bufferTime} mins`);
        console.log(`Total slot time: ${totalSlotTime} mins`);
        console.log(`Rendering time slots from ${startHour}:00 to ${endHour}:00`);
        
        // NEW: Adjust end time based on service duration and buffer
        // This ensures we don't create slots that would go past the end time
        const adjustedEndHour = endHour - Math.ceil(totalSlotTime / 60);
        
        // If service duration is over 3 hours, offer morning/afternoon options
        if (serviceDuration >= 180) {
            // Find midpoint for morning/afternoon split
            const midPoint = startHour + Math.floor((endHour - startHour) / 2);
            
            // Check if morning slot is available
            const isMorningBooked = isTimeSlotBooked(startHour, midPoint, bookedTimes, totalSlotTime);
            
            // Check if afternoon slot is available
            const isAfternoonBooked = isTimeSlotBooked(midPoint, endHour, bookedTimes, totalSlotTime);
            
            // Morning slot button
            const morningBtn = document.createElement('button');
            morningBtn.type = 'button';
            morningBtn.className = `time-btn col-span-2 text-sm py-3 border ${isMorningBooked ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'border-gray-200 rounded hover:border-gray-300'} mb-2`;
            morningBtn.dataset.time = `${String(startHour).padStart(2, '0')}:00`;
            morningBtn.dataset.duration = serviceDuration;
            
            // Display duration including buffer time in the button text
            const morningEndTime = midPoint;
            morningBtn.textContent = `Morning (${convertTo12HourFormat(startHour)} - ${convertTo12HourFormat(morningEndTime)})`;
            morningBtn.title = `Duration: ${serviceDuration} mins + ${bufferTime} mins buffer`;
            
            if (isMorningBooked) {
                morningBtn.disabled = true;
                morningBtn.textContent += ' (Booked)';
            }
            
            // Afternoon slot button
            const afternoonBtn = document.createElement('button');
            afternoonBtn.type = 'button';
            afternoonBtn.className = `time-btn col-span-2 text-sm py-3 border ${isAfternoonBooked ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'border-gray-200 rounded hover:border-gray-300'}`;
            afternoonBtn.dataset.time = `${String(midPoint).padStart(2, '0')}:00`;
            afternoonBtn.dataset.duration = serviceDuration;
            
            // Display duration including buffer time in the button text
            afternoonBtn.textContent = `Afternoon (${convertTo12HourFormat(midPoint)} - ${convertTo12HourFormat(endHour)})`;
            afternoonBtn.title = `Duration: ${serviceDuration} mins + ${bufferTime} mins buffer`;
            
            if (isAfternoonBooked) {
                afternoonBtn.disabled = true;
                afternoonBtn.textContent += ' (Booked)';
            }
            
            timeButtonsContainer.appendChild(morningBtn);
            timeButtonsContainer.appendChild(afternoonBtn);
        }
        // For services between 1-3 hours, offer 2-hour blocks
        else if (serviceDuration >= 60 && serviceDuration < 180) {
            // Calculate how many hours the service + buffer will take
            const slotHours = Math.ceil(totalSlotTime / 60);
            
            for (let hour = startHour; hour <= adjustedEndHour; hour += slotHours) {
                // Make sure we don't create slots that extend beyond the end time
                if (hour + slotHours <= endHour) {
                    // Check if any hour in this block is booked
                    const isBlockBooked = isTimeSlotBooked(hour, hour + slotHours, bookedTimes, totalSlotTime);
                    
                    const blockBtn = document.createElement('button');
                    blockBtn.type = 'button';
                    blockBtn.className = `time-btn text-sm py-3 border ${isBlockBooked ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'border-gray-200 rounded hover:border-gray-300'} mb-2`;
                    blockBtn.dataset.time = `${String(hour).padStart(2, '0')}:00`;
                    blockBtn.dataset.duration = serviceDuration;
                    
                    // Display duration including buffer time in the button text
                    const serviceEndHour = hour + Math.ceil(serviceDuration / 60);
                    blockBtn.textContent = `${convertTo12HourFormat(hour)} - ${convertTo12HourFormat(serviceEndHour)}`;
                    
                    // Add buffer time indicator if there is a buffer
                    if (bufferTime > 0) {
                        blockBtn.textContent += ` (+${bufferTime}m)`;
                    }
                    
                    if (isBlockBooked) {
                        blockBtn.disabled = true;
                        blockBtn.textContent += ' (Booked)';
                    }
                    
                    timeButtonsContainer.appendChild(blockBtn);
                }
            }
        }
        // For shorter services (less than 1 hour), show hourly slots
        else {
            // Generate hourly time slots
            for (let hour = startHour; hour <= adjustedEndHour; hour++) {
                // Format hour with padding
                const timeStr = `${String(hour).padStart(2, '0')}:00`;
                
                // Check if this time slot is booked
                // Here we consider both the service duration and buffer time
                const isBooked = isTimeSlotBooked(hour, hour + Math.ceil(totalSlotTime / 60), bookedTimes, totalSlotTime);
                
                // Create the time slot button
                const timeBtn = document.createElement('button');
                timeBtn.type = 'button';
                timeBtn.className = `time-btn text-sm py-2 border ${isBooked ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'border-gray-200 rounded hover:border-gray-300'}`;
                timeBtn.dataset.time = timeStr;
                timeBtn.dataset.duration = serviceDuration;
                
                // Calculate end time including service duration but not buffer
                const serviceEndHour = hour + Math.ceil(serviceDuration / 60);
                const serviceEndMinutes = (serviceDuration % 60);
                
                // Show the actual service time without buffer
                timeBtn.textContent = convertTo12HourFormat(hour);
                
                // Add buffer time indicator if there is a buffer
                if (bufferTime > 0) {
                    timeBtn.title = `Duration: ${serviceDuration} mins + ${bufferTime} mins buffer`;
                    
                    // For shorter durations, optionally show the end time
                    if (serviceDuration <= 60) {
                        const endTimeStr = serviceEndMinutes > 0 ? 
                            `${hour}:${serviceEndMinutes}` : 
                            `${hour + 1}:00`;
                        timeBtn.textContent = `${convertTo12HourFormat(hour)}`;
                    }
                }
                
                if (isBooked) {
                    timeBtn.disabled = true;
                    timeBtn.textContent += ' (Booked)';
                }
                
                timeButtonsContainer.appendChild(timeBtn);
            }
        }
        
        // Add click event to time buttons
        document.querySelectorAll(".time-btn:not([disabled])").forEach((button) => {
            button.addEventListener("click", function() {
                document.querySelectorAll(".time-btn").forEach((btn) => 
                    btn.classList.remove("selected", "bg-blue-600", "text-white"));
                this.classList.add("selected", "bg-blue-600", "text-white");
                document.getElementById("selectedTime").value = this.getAttribute("data-time");
                document.getElementById("selectedDuration").value = this.getAttribute("data-duration");
                checkFormValidity();
            });
        });
    } else {
        timeButtonsContainer.innerHTML = `
            <p class="col-span-2 text-sm text-gray-600 py-4 text-center">
                No available time slots for this date.
            </p>`;
    }
}

// NEW: Helper function to check if a time slot is booked
function isTimeSlotBooked(startHour, endHour, bookedTimes, totalSlotMinutes) {
    // Convert hours to minutes for easier comparison
    const startMinutes = startHour * 60;
    const endMinutes = endHour * 60;
    
    return bookedTimes.some(time => {
        const parts = time.split(':');
        const bookedHour = parseInt(parts[0]);
        const bookedMinute = parts.length > 1 ? parseInt(parts[1]) : 0;
        const bookedTimeInMinutes = bookedHour * 60 + bookedMinute;
        
        // Check if the booked time overlaps with this slot
        return (bookedTimeInMinutes >= startMinutes && bookedTimeInMinutes < endMinutes) || 
               (bookedTimeInMinutes + totalSlotMinutes > startMinutes && bookedTimeInMinutes < startMinutes);
    });
}

    // Helper function to convert 24-hour time to 12-hour format
    function convertTo12HourFormat(hour) {
        const period = hour >= 12 ? "PM" : "AM";
        const hour12 = hour % 12 || 12; // Convert 0 to 12 for midnight
        return `${hour12}:00 ${period}`;
    }

    // Event listeners for navigation buttons
    nextMonthButton.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        updateCalendar();
    });

    prevMonthButton.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        updateCalendar();
    });

    // Location-based restriction logic
    bookButton.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent default modal opening
        const locationRestriction = document.getElementById("locationRestriction").value;
        console.log("Location restriction:", locationRestriction);
        
        // Only check location if restriction is 'minglanilla_only'
        if (locationRestriction === "minglanilla_only") {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    // Location check for minglanilla_only services
                    const minglanillaLat = 10.2451;
                    const minglanillaLng = 123.7857;
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;

                    function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
                        const R = 6371;
                        const dLat = (lat2 - lat1) * Math.PI / 180;
                        const dLon = (lon2 - lon1) * Math.PI / 180;
                        const a =
                            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                            Math.sin(dLon / 2) * Math.sin(dLon / 2);
                        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                        return R * c;
                    }
                    function isInMinglanilla(lat, lng) {
                        return getDistanceFromLatLonInKm(lat, lng, minglanillaLat, minglanillaLng) <= 5;
                    }

                    if (!isInMinglanilla(userLat, userLng)) {
                        showLocationRestrictionModal();
                        return;
                    } else {
                        // Only show booking modal if location is valid
                        bookingModal.classList.remove("hidden");
                        currentDate = new Date();
                        updateCalendar();
                    }
                }, function (error) {
                    // Error handling for geolocation permission denied
                    showLocationPermissionModal();
                });
            } else {
                // Browser doesn't support geolocation
                showBrowserNotSupportedModal();
            }
        } else {
            // For "open" services, show booking modal without location check
            bookingModal.classList.remove("hidden");
            currentDate = new Date();
            updateCalendar();
        }
    });

    // Modal close events
    closeModal.addEventListener("click", () => {
        bookingModal.classList.add("hidden");
    });

    bookingModal.addEventListener("click", (e) => {
        if (e.target === bookingModal) {
            bookingModal.classList.add("hidden");
        }
    });

    // Error scenario modals
    window.showLocationRestrictionModal = function () {
        const modal = document.createElement("div");
        modal.className = "fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50";
        modal.innerHTML = `
            <div class="bg-white rounded-xl shadow-lg p-8 max-w-sm mx-auto text-center">
                <h3 class="text-lg font-semibold mb-4 text-red-600">Service Unavailable</h3>
                <p class="mb-6 text-gray-700">This service is only available in Minglanilla, Cebu.<br>You are currently outside the service area.</p>
                <button class="px-4 py-2 bg-primary text-white rounded" onclick="this.closest('.fixed').remove()">Close</button>
            </div>
        `;
        document.body.appendChild(modal);
    };
    
    window.showLocationPermissionModal = function () {
        const modal = document.createElement("div");
        modal.className = "fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50";
        modal.innerHTML = `
            <div class="bg-white rounded-xl shadow-lg p-8 max-w-sm mx-auto text-center">
                <h3 class="text-lg font-semibold mb-4 text-yellow-600">Location Access Required</h3>
                <p class="mb-6 text-gray-700">Please enable location access to book this service.<br>Location is needed to verify you are in the service area.</p>
                <button class="px-4 py-2 bg-primary text-white rounded" onclick="this.closest('.fixed').remove()">Close</button>
            </div>
        `;
        document.body.appendChild(modal);
    };
    
    window.showBrowserNotSupportedModal = function () {
        const modal = document.createElement("div");
        modal.className = "fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50";
        modal.innerHTML = `
            <div class="bg-white rounded-xl shadow-lg p-8 max-w-sm mx-auto text-center">
                <h3 class="text-lg font-semibold mb-4 text-red-600">Browser Not Supported</h3>
                <p class="mb-6 text-gray-700">Your browser doesn't support location services.<br>Please use a different browser or device.</p>
                <button class="px-4 py-2 bg-primary text-white rounded" onclick="this.closest('.fixed').remove()">Close</button>
            </div>
        `;
        document.body.appendChild(modal);
    };

    // Form submission handler
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        document.getElementById('notesInput').value = document.getElementById('notes').value;
       
        // Validation for date and time
        const date = document.getElementById('selectedDate').value;
        const time = document.getElementById('selectedTime').value;
        const duration = document.getElementById('selectedDuration').value;
        
        if (!date || !time) {
            e.preventDefault();
            alert('Please select both a date and a time before proceeding to payment.');
            return false;
        }

        if (!duration) {
            document.getElementById('selectedDuration').value = document.getElementById('serviceDuration').value;
        }
        
        const submitBtn = this.querySelector('button[type="submit"]');
        showSpinnerOnButton(submitBtn);
    });

    // Button state management functions
    function showSpinnerOnButton(button) {
        const btnText = button.querySelector('.btn-text');
        const btnSpinner = button.querySelector('.btn-spinner');
        button.disabled = true;
        button.classList.add('disabled');
        if (btnText) btnText.style.display = 'none';
        if (btnSpinner) btnSpinner.style.display = 'inline-block';
    }
    
    function restoreButton(button, text) {
        const btnText = button.querySelector('.btn-text');
        const btnSpinner = button.querySelector('.btn-spinner');
        button.disabled = false;
        button.classList.remove('disabled');
        if (btnText) btnText.style.display = 'inline-block';
        if (btnSpinner) btnSpinner.style.display = 'none';
        if (btnText && text) btnText.textContent = text;
    }

    // Form validation function
    function checkFormValidity() {
        const dateSelected = document.getElementById('selectedDate').value;
        const timeSelected = document.getElementById('selectedTime').value;
        
        if (dateSelected && timeSelected) {
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            validationMsg.style.display = 'none';
        } else {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            validationMsg.style.display = 'block';
        }
    }
});
      </script>
  </body>
</html>
