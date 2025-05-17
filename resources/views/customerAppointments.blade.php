<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Appointments</title>
    <link rel="stylesheet" href="{{ asset('css/customerAppointment.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
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
              primary: "#118f39",
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
</head>
<body>

<header class="sticky top-0 z-50 bg-white shadow-sm">
      <div class="flex items-center justify-between px-8 h-16">
      <a href="/" class="font-poppins text-2xl font-extrabold">
          <span class="text-[#118f39]">Mingla</span><span class="text-[#4CAF50]">Gawa</span> 
        </a>
        <div class="flex items-center flex-1 max-w-xl mx-8">
          <div class="relative w-full">
          <form action="{{ route('search') }}" method="GET" style="display: flex; align-items: center; width: 100%;">
            <input
              type="text"
              name="q" 
              class="search-bar w-full h-10 pl-10 pr-4 text-sm bg-gray-50 border-none !rounded-button"
              placeholder="Search for services or freelancers..."
              value="{{ request('q') }}"
            />
            <div
              class="absolute left-3 top-0 w-4 h-10 flex items-center justify-center text-gray-400"
            >
              <i class="ri-search-line"></i>
            </div>
            </form>
          </div>
        </div>
        <div class="flex items-center gap-6">
        <div class="relative inline-block text-left">
    <!-- Notification Icon -->
    <button id="notification-icon" class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-primary">
        <div class="relative w-6 h-6 flex items-center justify-center">
            <i class="ri-notification-line"></i>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </div>
        <span>Notifications</span>
    </button>

    <!-- Notification Dropdown -->
    <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg z-50">
        <div class="p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-700">Notifications</h3>
        </div>
        <ul id="notification-list" class="max-h-64 overflow-y-auto">
            @foreach(auth()->user()->notifications as $notification)
                <li class="flex items-start justify-between px-4 py-3 border-b hover:bg-gray-50 {{ $notification->read_at ? 'bg-gray-100' : '' }}">
                    <div>
                        <p class="text-sm {{ $notification->read_at ? 'text-gray-500' : 'text-gray-600' }}">
                            {{ $notification->data['message'] }}
                        </p>
                        <p class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$notification->read_at)
                        <button
                            class="text-sm text-primary hover:underline mark-as-read"
                            data-id="{{ $notification->id }}"
                        >
                            Mark as Read
                        </button>
                    @endif
                </li>
            @endforeach
        </ul>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <div class="p-4 border-t">
                <button id="mark-all-read" class="w-full py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 rounded-lg">
                    Mark All as Read
                </button>
            </div>
        @endif
    </div>
</div>
                    <button
            class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-primary !rounded-button"
            >
            <div class="relative w-6 h-6 flex items-center justify-center">
                <i class="ri-message-3-line"></i>
                <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                3
                </span>
            </div>
            Messages
            </button>
           
<div class="relative inline-block text-left">

  <button id="profileBtn" class="w-12 h-12 rounded-full overflow-hidden focus:outline-none">
    <img  src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/defaultprofile.png') }}" alt="User" class="w-full h-full object-cover" />
  </button>


  <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg z-50">

    <div class="flex items-center gap-3 p-4 border-b">
      <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/defaultprofile.png') }}" alt="User" class="w-12 h-12 rounded-full object-cover" />
      <div>
        <p class="font-bold leading-tight">{{ $user->firstname }} {{ $user->lastname }}</p>
      
      </div>
    </div>

 
    <!-- Menu Items -->
<ul class="py-2 text-sm text-gray-700">
  <li>
    <a href="{{ route('customer.profile') }}" class="flex items-center px-4 py-2 hover:bg-gray-100">
      <!-- Profile Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 017 16h10a4 4 0 011.879.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
      Profile
    </a>
  </li>
  
  <li>
    <a href="{{ route('customer.appointments.view') }}" class="flex items-center px-4 py-2 hover:bg-gray-100">
      <!-- Appointment Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-13 5h16a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v7a2 2 0 002 2z" />
      </svg>
      Appointment
    </a>
  </li>

  <li>
    <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100">
      <!-- Settings Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      Settings
    </a>
  </li>
  
  <li>
    <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100">
      <!-- Help Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h4l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2z" />
      </svg>
      Help
    </a>
  </li>
  
  <li>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
      </form>
    <a href="#"onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center px-4 py-2 text-red-600 hover:bg-gray-100">
      <!-- Logout Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
      </svg>
      Logout
    </a>
  </li>
</ul>

  </div>
</div>

        </div>

      </div>
    </header>

    <main>
    <a href="{{ route('customer.dashboard') }}">
            <button class="back-btn">Back</button>
        </a>
    @if(session('success'))
        <div class="alert alert-success">
        <i class='bx bx-check-circle'></i> <!-- Success icon -->
        {{ session('success') }}
        </div>
        @endif

        <div id="calendar-container">
          <div id="calendar"></div>
      </div>

     <!-- Appointment Details Modal -->
<div id="appointmentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <div class="flex items-center gap-2">
                <i class="ri-calendar-check-line text-2xl text-primary"></i>
                <h5 class="text-lg font-semibold text-gray-800">Appointment Details</h5>
            </div>
            <button type="button" class="text-gray-400 hover:text-gray-600 close-modal">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        <div class="px-6 py-4 space-y-3">
            <div class="flex items-center gap-2">
                <i class="ri-user-line text-primary"></i>
                <span class="font-medium">Freelancer Name:</span>
                <span id="freelancerName"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="ri-calendar-line text-primary"></i>
                <span class="font-medium">Date:</span>
                <span id="appointmentDate"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="ri-time-line text-primary"></i>
                <span class="font-medium">Time:</span>
                <span id="appointmentTime"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="ri-map-pin-line text-primary"></i>
                <span class="font-medium">Address:</span>
                <span id="appointmentAddress"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="ri-information-line text-primary"></i>
                <span class="font-medium">Status:</span>
                <span id="appointmentStatus"></span>
            </div>
            <div id="declineReasonContainer" class="flex items-center gap-2" style="display: none;">
                <i class="ri-error-warning-line text-red-500"></i>
                <span class="font-medium">Reason for Decline:</span>
                <span id="declineReason"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="ri-sticky-note-line text-primary"></i>
                <span class="font-medium">Notes:</span>
                <span id="appointmentNotes"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="ri-money-dollar-circle-line text-primary"></i>
                <span class="font-medium">Commitment Fee Status:</span>
                <span id="feeStatus"></span>
            </div>
        </div>
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button id="rescheduleButton" class="btn btn-primary flex items-center gap-1">
                <i class="ri-calendar-event-line"></i> Re-schedule
            </button>
            <button id="cancelButton" class="btn btn-danger flex items-center gap-1">
                <i class="ri-close-circle-line"></i> Cancel
            </button>
            <button id="rateButton" class="btn btn-success flex items-center gap-1" style="display: none;">
                <i class="ri-star-line"></i> Rate & Review
            </button>
            <button type="button" class="btn btn-secondary close-modal flex items-center gap-1">
                <i class="ri-arrow-go-back-line"></i> Close
            </button>
            <form id="noShowForm" method="POST" action="{{ route('appointments.no_show', 0) }}" style="display:none;">
                @csrf
                <button type="submit" class="btn btn-warning flex items-center gap-1">
                    <i class="ri-error-warning-line"></i> Mark as No-Show
                </button>
            </form>
        </div>
    </div>
</div>
            
<div id="rescheduleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <div class="flex items-center gap-2">
                <i class="ri-calendar-event-line text-xl text-primary"></i>
                <h5 class="text-lg font-semibold text-gray-800">Reschedule Appointment</h5>
            </div>
        </div>
        <div class="px-6 py-4">
            <form id="rescheduleForm">
                <!-- Date selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select a new date:</label>
                    <div id="availableDates" class="grid grid-cols-3 gap-2">
                        <!-- Date buttons will be inserted here -->
                    </div>
                </div>
                
                <!-- Time selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select a new time:</label>
                    <div id="availableTimes" class="grid grid-cols-3 gap-2">
                        <!-- Time buttons will be inserted here -->
                    </div>
                </div>
                
                <input type="hidden" id="selectedDate" name="date">
                <input type="hidden" id="selectedTime" name="time">
            </form>
        </div>
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button type="button" class="btn btn-secondary flex items-center gap-1 close-modal">
                <i class="ri-arrow-go-back-line"></i> Cancel
            </button>
            <button type="submit" class="btn btn-primary flex items-center gap-1" id="saveReschedule">
                <i class="ri-save-line"></i> Save Changes
            </button>
        </div>
    </div>
</div>

 <!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <div class="flex items-center gap-2">
                <i class="ri-star-line text-xl text-yellow-400"></i>
                <h5 class="text-lg font-semibold text-gray-800">Rate and Review</h5>
            </div>
            <button type="button" class="text-gray-400 hover:text-gray-600 close-modal">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        <div class="px-6 py-4">
            <form id="reviewForm">
                <input type="hidden" id="appointmentId" value="">
                <div class="mb-4">
                    <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                    <div id="starRating" class="flex space-x-2 mt-2">
                        <span class="star text-gray-400 text-3xl cursor-pointer" data-value="1">&#9733;</span>
                        <span class="star text-gray-400 text-3xl cursor-pointer" data-value="2">&#9733;</span>
                        <span class="star text-gray-400 text-3xl cursor-pointer" data-value="3">&#9733;</span>
                        <span class="star text-gray-400 text-3xl cursor-pointer" data-value="4">&#9733;</span>
                        <span class="star text-gray-400 text-3xl cursor-pointer" data-value="5">&#9733;</span>
                    </div>
                    <input type="hidden" id="rating" name="rating" value="0">
                </div>
                <div class="mb-4">
                    <label for="review" class="block text-sm font-medium text-gray-700">Review</label>
                    <textarea id="review" name="review" class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" rows="4" placeholder="Write your review here..."></textarea>
                </div>
                <button type="button" id="submitReview" class="w-full py-2 text-white bg-primary hover:bg-primary/90 rounded-lg flex items-center justify-center gap-2">
                    <i class="ri-send-plane-line"></i> Submit Review
                </button>
            </form>
        </div>
    </div>
</div>
    </main>

    
    <script>

const profileBtn = document.getElementById('profileBtn');
          const dropdownMenu = document.getElementById('dropdownMenu');

          profileBtn.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
          });

          document.addEventListener('click', (e) => {
            if (!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
              dropdownMenu.classList.add('hidden');
            }
          });
       
       document.addEventListener('DOMContentLoaded', function () {
    const notificationIcon = document.getElementById('notification-icon');
    const notificationDropdown = document.getElementById('notification-dropdown');
    const markAsReadButtons = document.querySelectorAll('.mark-as-read');
    const markAllReadButton = document.getElementById('mark-all-read');

    // Toggle Notification Dropdown
    notificationIcon.addEventListener('click', function () {
        notificationDropdown.classList.toggle('hidden');
    });

    // Mark Individual Notification as Read
    markAsReadButtons.forEach(button => {
        button.addEventListener('click', function () {
            const notificationId = this.getAttribute('data-id');
            fetch(`/notifications/mark-as-read/${notificationId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const notificationItem = this.closest('li');
                        notificationItem.classList.add('bg-gray-100');
                        notificationItem.querySelector('p').classList.replace('text-gray-600', 'text-gray-500');
                        this.remove(); // Remove the "Mark as Read" button
                        updateNotificationCount();
                    }
                });
        });
    });

    // Mark All Notifications as Read
    if (markAllReadButton) {
        markAllReadButton.addEventListener('click', function () {
            fetch(`/notifications/mark-all-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const notificationItems = document.querySelectorAll('#notification-list li');
                        notificationItems.forEach(item => {
                            item.classList.add('bg-gray-100');
                            const message = item.querySelector('p');
                            if (message) {
                                message.classList.replace('text-gray-600', 'text-gray-500');
                            }
                            const markAsReadButton = item.querySelector('.mark-as-read');
                            if (markAsReadButton) {
                                markAsReadButton.remove();
                            }
                        });
                        updateNotificationCount();
                    }
                });
        });
    }

    // Update Notification Count
    function updateNotificationCount() {
        const countElement = notificationIcon.querySelector('span');
        const currentCount = parseInt(countElement.textContent) || 0;
        if (currentCount > 1) {
            countElement.textContent = currentCount - 1;
        } else {
            countElement.remove();
        }
    }
});


      let calendar;  
  document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'en',
        height: 600,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: '/customer/appointments', // Fetch events dynamically
        eventClick: function (info) {
            const eventId = info.event.id;

            // Fetch event details via AJAX
            fetch(`/customer/appointments/${eventId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Full appointment data:', data);
                    openAppointmentModal(data); // Open modal with fetched data
                })
                .catch(error => {
                    console.error('Error fetching appointment details:', error);
                });
        }
    });

    calendar.render();
});

function handleButtonVisibility(status) {
    const rescheduleButton = document.getElementById('rescheduleButton');
    const cancelButton = document.getElementById('cancelButton');
    const rateButton = document.getElementById('rateButton');

    // Hide or show buttons based on status
    rescheduleButton.style.display = 'inline-block';
    cancelButton.style.display = 'inline-block';
    rateButton.style.display = 'none';

    if (status.toLowerCase() === 'declined') {
        rescheduleButton.style.display = 'none'; // Hide reschedule button
        cancelButton.style.display = 'none'; // Hide cancel button
    } else if (status.toLowerCase() === 'completed') {
        rateButton.style.display = 'inline-block'; // Show rate button
        rescheduleButton.style.display = 'none'; // Hide reschedule button
        cancelButton.style.display = 'none'; // Hide cancel button
    } else if (status.toLowerCase() === 'accepted') {
        rescheduleButton.style.display = 'none'; // Hide reschedule button
        cancelButton.style.display = 'inline-block'; // Hide cancel button
     } else if (status.toLowerCase() === 'canceled') {
        // Hide both buttons if status is canceled
        rescheduleButton.style.display = 'none';
        cancelButton.style.display = 'none';
    } else {
        // Default to showing both reschedule and cancel buttons for other statuses
        rescheduleButton.style.display = 'inline-block';
        cancelButton.style.display = 'inline-block';
    }
}
function openAppointmentModal(data) {
    // Populate modal with appointment details
    console.log('Full appointment data:', data);
    console.log('Decline Reason:', data.decline_reason); // Debugging
    document.getElementById('freelancerName').textContent = data.freelancer_name || 'N/A';
    document.getElementById('appointmentDate').textContent = data.date || 'N/A';
    document.getElementById('appointmentTime').textContent = data.time || 'N/A';
    document.getElementById('appointmentAddress').textContent = data.address || 'N/A';
    document.getElementById('appointmentStatus').textContent = data.status || 'N/A';
    document.getElementById('appointmentNotes').textContent = data.notes || 'No additional notes';
    document.getElementById('feeStatus').textContent = data.fee_status || 'N/A';
    
    document.getElementById('appointmentId').value = data.id;
    
    // Show the decline reason if the status is "declined"
    const declineReasonContainer = document.getElementById('declineReasonContainer');
    const declineReason = document.getElementById('declineReason');
    
    if (data.status.toLowerCase() === 'declined') {
        declineReasonContainer.style.display = 'block';
        declineReason.textContent = data.decline_reason ? data.decline_reason : 'No reason provided';
    } else {
        declineReasonContainer.style.display = 'none';
    }
    
    
    // Show or hide buttons based on appointment status
   
    handleButtonVisibility(data.status);

     // Real-time No-Show Button Logic
     const noShowForm = document.getElementById('noShowForm');
    if (noShowForm) {
        // Set the correct action URL for the appointment
        noShowForm.action = `/appointments/${data.id}/no-show`;

        // Function to check if button should be shown
        function updateNoShowButton() {
            const appointmentStatus = data.status ? data.status.toLowerCase() : '';
            const appointmentTime24 = to24HourTime(data.time); // Convert to 24-hour
            const appointmentDateTime = new Date(data.date + 'T' + appointmentTime24);
            const now = new Date();

            if (appointmentStatus === 'accepted' && now > appointmentDateTime) {
                noShowForm.style.display = 'inline';
            } else {
                noShowForm.style.display = 'none';
            }
        }

        updateNoShowButton();
        // Check every minute for real-time update
        window.noShowInterval && clearInterval(window.noShowInterval);
        window.noShowInterval = setInterval(updateNoShowButton, 60000);
    }

    
    
  // ...inside openAppointmentModal(data)...
document.getElementById('rescheduleButton').onclick = function () {
    document.getElementById('appointmentModal').style.display = 'none';
    document.getElementById('rescheduleModal').style.display = 'flex';

     const datesContainer = document.getElementById('availableDates');
    const timesContainer = document.getElementById('availableTimes');
    const selectedDateInput = document.getElementById('selectedDate');
    const selectedTimeInput = document.getElementById('selectedTime');
    
    // Clear previous selections
    datesContainer.innerHTML = '<div class="col-span-3 text-center py-2">Loading available dates...</div>';
    timesContainer.innerHTML = '<div class="col-span-3 text-center py-2">Select a date first</div>';
    
    console.log('Fetching availability for freelancer:', data.freelancer_id, 'with appointment ID:', data.id);
    
    fetch(`/freelancer/${data.freelancer_id}/availability?current_appointment_id=${data.id}`)
        .then(response => response.json())  
        .then(availabilities => {
            console.log('Received availabilities:', availabilities);
            
            // Get unique dates
            const uniqueDates = [...new Set(availabilities.map(a => a.date))];
            datesContainer.innerHTML = '';
            
            if (uniqueDates.length === 0) {
                datesContainer.innerHTML = '<div class="col-span-3 text-center py-2">No available dates</div>';
                return;
            }
            
            // Create date buttons
            uniqueDates.forEach(date => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'date-btn px-2 py-3 border rounded text-sm';
                
                // Format date for display
                const d = new Date(date);
                btn.innerHTML = `${d.getDate()}<br><span class="text-xs">${d.toLocaleString('default', { month: 'short' })}</span>`;
                btn.dataset.date = date;
                
                btn.addEventListener('click', function() {
                    // Update selection styling
                    document.querySelectorAll('.date-btn').forEach(b => 
                        b.classList.remove('bg-primary', 'text-white'));
                    this.classList.add('bg-primary', 'text-white');
                    
                    // Store selected date
                    selectedDateInput.value = this.dataset.date;
                    
                    // Update available times
                    updateAvailableTimes(this.dataset.date, availabilities);
                });
                
                datesContainer.appendChild(btn);
            });
            
            // Select first date by default
            if (uniqueDates.length > 0) {
                const firstDateBtn = datesContainer.querySelector('.date-btn');
                firstDateBtn.click();
            }
        })
        .catch(error => {
            console.error('Error loading availabilities:', error);
            datesContainer.innerHTML = '<div class="col-span-3 text-center py-2">Error loading dates: ' + error.message + '</div>';
        });
        
  function updateAvailableTimes(selectedDate, availabilities) {
    timesContainer.innerHTML = '<div class="col-span-3 text-center py-2">Loading times...</div>';
    
    const dateAvailability = availabilities.find(a => a.date === selectedDate);
    
    if (!dateAvailability) {
        timesContainer.innerHTML = '<div class="col-span-3 text-center py-2">No times available</div>';
        return;
    }
    
    // Get times
    const start = dateAvailability.start_time.split(':').map(Number);
    const end = dateAvailability.end_time.split(':').map(Number);
    const bookedTimes = dateAvailability.booked_times || [];
    
    timesContainer.innerHTML = '';
    let hasAvailableTimes = false;
    
    // Generate 1-hour time slots (instead of 30-minute slots)
    for (let h = start[0]; h < end[0]; h++) {
        const timeStr = `${h.toString().padStart(2, '0')}:00`;
        const isBooked = bookedTimes.includes(timeStr);
        
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = isBooked 
            ? 'time-btn px-3 py-2 border rounded bg-gray-200 text-gray-500 cursor-not-allowed'
            : 'time-btn px-3 py-2 border rounded';
        
        // Format time for display
        const timeDisplay = new Date(`2000-01-01T${timeStr}`).toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
        
        btn.textContent = timeDisplay;
        btn.dataset.time = timeStr;
        
        // Disable button if time is booked
        btn.disabled = isBooked;
        
        if (!isBooked) {
            hasAvailableTimes = true;
            btn.addEventListener('click', function() {
                // Update selection styling
                document.querySelectorAll('.time-btn').forEach(b => 
                    b.classList.remove('bg-primary', 'text-white'));
                this.classList.add('bg-primary', 'text-white');
                
                // Store selected time
                selectedTimeInput.value = this.dataset.time;
            });
        }
        
        timesContainer.appendChild(btn);
    }
    
    if (!hasAvailableTimes) {
        timesContainer.innerHTML = '<div class="col-span-3 text-center py-2">No available times for this date</div>';
    }
}
// Handle form submission
    document.getElementById('saveReschedule').onclick = function(e) {
        e.preventDefault();
        
        const newDate = selectedDateInput.value;
        const newTime = selectedTimeInput.value;
        
        if (!newDate || !newTime) {
            alert('Please select both a date and time');
            return;
        }
        
        fetch(`/customer/appointments/reschedule/${data.id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ date: newDate, time: newTime }),
        })
        .then(response => response.json())
        .then(result => {
            alert(result.message);
            document.getElementById('rescheduleModal').style.display = 'none';
            location.reload();
        })
        .catch(error => {
            console.error('Error rescheduling appointment:', error);
            alert('Failed to reschedule appointment. Please try again.');
        });
    };
};
    

    function to24HourTime(time12h) {
    // time12h: "09:30 AM" or "04:00 PM"
    const [time, modifier] = time12h.split(' ');
    let [hours, minutes] = time.split(':');
    if (hours === '12') {
        hours = '00';
    }
    if (modifier === 'PM') {
        hours = parseInt(hours, 10) + 12;
    }
    return `${hours.toString().padStart(2, '0')}:${minutes}:00`;
}
    // Add event listener for the cancel button
    document.getElementById('cancelButton').onclick = function () {
        if (confirm('Are you sure you want to cancel this appointment?')) {
            fetch(`/customer/appointments/cancel/${data.id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(result => {
                    alert(result.message || result.error);
                    location.reload(); // Refresh the calendar
                })
                .catch(error => {
                    console.error('Error canceling appointment:', error);
                    alert('Failed to cancel appointment. Please try again.');
                });
        }
    };

   

    // Show the appointment modal
    document.getElementById('appointmentModal').style.display = 'flex';
}

function closeAllModals() {
    document.getElementById('appointmentModal').style.display = 'none';
    document.getElementById('rescheduleModal').style.display = 'none';
    document.getElementById('reviewModal').style.display = 'none';
}

// Attach close modal listeners
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent form submission if inside a form
            closeAllModals();
        });
    }); 

document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('#starRating .star');
    const ratingInput = document.getElementById('rating');

    stars.forEach(star => {
        // Highlight stars on hover
        star.addEventListener('mouseover', function () {
            const value = this.getAttribute('data-value');
            highlightStars(value);
        });

        // Reset stars when not hovering
        star.addEventListener('mouseout', function () {
            highlightStars(ratingInput.value); // Reset to the selected rating
        });

        // Set the rating on click
        star.addEventListener('click', function () {
            const value = this.getAttribute('data-value');
            ratingInput.value = value; // Update the hidden input value
            highlightStars(value);
        });
    });

    // Function to highlight stars
    function highlightStars(value) {
        stars.forEach(star => {
            if (star.getAttribute('data-value') <= value) {
                star.classList.add('selected');
            } else {
                star.classList.remove('selected');
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const appointmentModal = document.getElementById('appointmentModal');
    const reviewModal = document.getElementById('reviewModal');
    const rateButton = document.getElementById('rateButton');
    const closeButtons = document.querySelectorAll('.close-modal');

    // Open Review Modal
    rateButton.onclick = function () {
        console.log('Rate and Review button clicked'); // Debugging
        // Hide the appointment modal
        appointmentModal.style.display = 'none';

        // Show the review modal
        reviewModal.style.display = 'flex';
    };

    // Close Modals
    closeButtons.forEach(button => {
        button.onclick = function () {
            console.log('Close button clicked'); // Debugging
            // Hide all modals
            appointmentModal.style.display = 'none';
            reviewModal.style.display = 'none';
        };
    });

    // Handle Review Submission
    document.getElementById('submitReview').onclick = function () {
        const rating = document.getElementById('rating').value;
        const review = document.getElementById('review').value;
        const appointmentId = document.getElementById('appointmentId').value;

        if (rating === "0") {
            alert('Please select a star rating.');
            return;
        }

        fetch(`/customer/appointments/review/${appointmentId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ rating: rating, review: review }),
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                console.log(result);
                alert(result.message || 'Review submitted successfully!');
                reviewModal.style.display = 'none'; // Close the review modal
                location.reload(); // Refresh the page to reflect the review
            })
            .catch(error => {
                console.error('Error submitting review:', error);
                alert('Failed to submit review. Please try again.');
            });
    };
});
// succes message time duration
document.addEventListener('DOMContentLoaded', function () {
              const alert = document.querySelector('.alert-success');
              if (alert) {
                  setTimeout(() => {
                      alert.remove();
                  }, 3000); // 3 seconds
              }
          });
</script>
 <!-- FullCalendar JS -->
 <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
</body>
</html>
