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
                                <span>{{ $freelancer->province }}, {{ $freelancer->city }}, {{ $freelancer->zipcode }}</span>
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
                        <div class="text-2xl font-semibold mb-1">98%</div>
                        <p class="text-sm text-gray-600">Job Success</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-semibold mb-1">156</div>
                        <p class="text-sm text-gray-600">Projects</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-semibold mb-1">2 hrs</div>
                        <p class="text-sm text-gray-600">Avg. Response</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-semibold mb-1">5+ yrs</div>
                        <p class="text-sm text-gray-600">Experience</p>
                    </div>
                </div>
               
            <p class="text-gray-600 leading-relaxed mb-8">
              I'm a results-driven digital marketing specialist with over 5
              years of experience helping businesses grow their online presence.
              My expertise spans across SEO, social media marketing, content
              strategy, and paid advertising. I take a data-driven approach to
              create comprehensive marketing strategies that deliver measurable
              results.
            </p>
                <!-- Portfolio Section -->
                <div class="border-t pt-8">
                    <h3 class="text-lg font-semibold mb-6">Recent works</h3>
                    <div class="grid grid-cols-2 gap-6">
                        @forelse ($freelancer->posts as $post)
                            @forelse ($post->pictures as $picture)
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
            <!-- Client Reviews Section -->
            <div class="bg-white rounded-lg p-8">
                <h3 class="text-lg font-semibold mb-6">Client Reviews</h3>
                <div class="space-y-6">
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
    </div>
    
   <!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 px-4">
  <div class="bg-white rounded-lg p-4 sm:p-6 w-full max-w-lg mx-auto relative" style="max-height:90vh;">
    <!-- Modal content remains the same but with adjusted padding -->
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-xl font-semibold">Book an Appointment</h3>
      <button id="closeModal" class="text-gray-400 hover:text-gray-600">
        <div class="w-6 h-6 flex items-center justify-center">
          <i class="ri-close-line"></i>
        </div>
      </button>
    </div>
    <form id="bookingForm" method="POST" action="{{ route('pay.commitment') }}" class="flex flex-col h-full">
      @csrf
      <input type="hidden" name="freelancer_id" value="{{ $freelancer->id }}">
      <input type="hidden" name="post_id" value="{{ $post->id }}">
      <input type="hidden" id="selectedDate" name="date" required>
      <input type="hidden" id="selectedTime" name="time" required>
      <input type="hidden" name="notes" id="notesInput">
      <input type="hidden" name="commitment_fee" value="{{ $commitment_fee ?? 100 }}">
      <!-- Scrollable Content -->
      <div class="flex-1 overflow-y-auto pr-2" style="max-height:60vh;">
        <!-- Calendar Section -->
        <div class="mb-4">
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
          <div class="grid grid-cols-3 gap-3"></div>
        </div>

        <!-- Notes Section -->
        <div class="mb-6">
          <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
          <textarea id="notes" name="notes" rows="3" class="w-full mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" placeholder="Add any additional details or instructions for the freelancer..."></textarea>
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

      <!-- Sticky Submit Button -->
      <div class="pt-4 bg-white sticky bottom-0 left-0 right-0">
        <button type="submit" class="w-full py-3 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded">
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
    const bookButton = document.getElementById("bookButton");
    const bookingModal = document.getElementById("bookingModal");
    const closeModal = document.getElementById("closeModal");
    const calendarGrid = document.getElementById("calendarGrid");
    const monthTitle = document.getElementById("monthTitle");
    const nextMonthButton = document.getElementById("nextMonthButton");
    const prevMonthButton = document.getElementById("prevMonthButton");
    const selectedDateInput = document.getElementById("selectedDate");
    const selectedTimeInput = document.getElementById("selectedTime");
    const timeButtonsContainer = document.querySelector(".grid-cols-3");

    const months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ];

    let currentDate = new Date();
    let freelancerId = "{{ $freelancer->id }}";

    // Function to fetch availability
    function fetchAvailability(year, month) {
    return fetch(`/freelancer/${freelancerId}/availability?year=${year}&month=${month + 1}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Fetched availability data:", data); // Debugging
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
    const timeButtonsContainer = document.querySelector("#bookingModal .grid-cols-3");
    timeButtonsContainer.innerHTML = `
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
        const timeButtonsContainer = document.querySelector("#bookingModal .grid-cols-3");
        timeButtonsContainer.innerHTML = `
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
        });
    });
}

function updateTimeSlots(selectedDate, availability) {
    const timeButtonsContainer = document.querySelector("#bookingModal .grid-cols-3");
    timeButtonsContainer.innerHTML = ""; // Clear previous time slots
    
     if (!availability || availability.length === 0) {
        timeButtonsContainer.innerHTML = `
            <p class="col-span-3 text-sm text-gray-600 py-4 text-center">
                No schedules have been set for this month.
                Please try selecting a different month.
            </p>`;
        return;
    }
    // Find the availability for the selected date
    const availableDay = availability.find(avail => avail.date === selectedDate);
    
    if (availableDay) {
        console.log("Available day data:", availableDay); // Debug
        
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
        
        console.log(`Rendering time slots from ${startHour}:00 to ${endHour}:00`);
        console.log("Booked times:", bookedTimes);
        
        // Generate time slots
        for (let hour = startHour; hour < endHour; hour++) {
            // Format hour with padding
            const timeStr = `${String(hour).padStart(2, '0')}:00`;
            const time12 = convertTo12HourFormat(hour);
            
            // Check if this time is booked
            const isBooked = bookedTimes.some(bookedTime => 
                bookedTime === timeStr || bookedTime.startsWith(timeStr + ':')
            );
            
            // Create the time slot button
            timeButtonsContainer.innerHTML += `
                <button
                    type="button"
                    class="time-btn text-sm py-2 border border-gray-200 rounded hover:border-gray-300 ${
                        isBooked ? "bg-gray-300 text-gray-500 cursor-not-allowed" : ""
                    }"
                    data-time="${timeStr}"
                    ${isBooked ? "disabled" : ""}
                >
                    ${time12} ${isBooked ? "(Booked)" : ""}
                </button>`;
        }
        
        // Add click event to time buttons
        document.querySelectorAll(".time-btn:not([disabled])").forEach((button) => {
            button.addEventListener("click", function() {
                document.querySelectorAll(".time-btn").forEach((btn) => 
                    btn.classList.remove("bg-blue-600", "text-white"));
                this.classList.add("bg-blue-600", "text-white");
                document.getElementById("selectedTime").value = this.getAttribute("data-time");
            });
        });
    } else {
        timeButtonsContainer.innerHTML = `
            <p class="col-span-3 text-sm text-gray-600 py-4 text-center">
                No available time slots for this date.
            </p>`;
    }
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

    // Open and close modal
    bookButton.addEventListener("click", () => {
        bookingModal.classList.remove("hidden");
        currentDate = new Date(); // Reset to current month/year
        updateCalendar(); // Initialize calendar when modal opens
});
    closeModal.addEventListener("click", () => {
        bookingModal.classList.add("hidden");
    });

    bookingModal.addEventListener("click", (e) => {
        if (e.target === bookingModal) {
            bookingModal.classList.add("hidden");
        }
    });
});


     

        document.getElementById('bookingForm').addEventListener('submit', function(e) {
    document.getElementById('notesInput').value = document.getElementById('notes').value;
   
   const submitBtn = this.querySelector('button[type="submit"]');
   showSpinnerOnButton(submitBtn);

   // Validation for date and time
   const date = document.getElementById('selectedDate').value;
    const time = document.getElementById('selectedTime').value;
    if (!date || !time) {
        e.preventDefault();
        alert('Please select both a date and a time before proceeding to payment.');
        return false;
    }
  
  });
      

      </script>
  </body>
</html>
