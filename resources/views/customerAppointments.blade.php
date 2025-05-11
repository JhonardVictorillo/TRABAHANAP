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
          <span class="text-[#118f39]">Mingla</span>&nbsp;<span class="text-[#4CAF50]">Gawa</span> 
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
<div id="appointmentModal" class="modal" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Appointment Details</h5>
                <button type="button" class="btn-close close-modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Freelancer Name:</strong> <span id="freelancerName"></span></p>
                <p><strong>Date:</strong> <span id="appointmentDate"></span></p>
                <p><strong>Time:</strong> <span id="appointmentTime"></span></p>
                <p><strong>Address:</strong> <span id="appointmentAddress"></span></p>
                <p><strong>Status:</strong> <span id="appointmentStatus"></span></p>
                <div id="declineReasonContainer" style="display: none;">
                    <p><strong>Reason for Decline:</strong> <span id="declineReason"></span></p>
                </div>
                <p><strong>Notes:</strong> <span id="appointmentNotes"></span></p>
            </div>

             
            <div class="modal-footer">
                <button id="rescheduleButton" class="btn btn-primary">Re-schedule</button>
                <button id="cancelButton" class="btn btn-danger">Cancel Appointment</button>
                <button id="rateButton" class="btn btn-success" style="display: none;">Rate and Review</button>
                <button type="button" class="btn btn-secondary close-modal">Close</button>
            </div>
        </div>
    </div>
</div>
            

<!-- Reschedule Appointment Modal -->
<div id="rescheduleModal" class="modal" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reschedule Appointment</h5>
                <button type="button" class="btn-close close-modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rescheduleForm">
                    <div class="form-group">
                        <label for="newDate">New Date:</label>
                        <input type="date" id="newDate" name="date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="newTime">New Time:</label>
                        <input type="time" id="newTime" name="time" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="saveReschedule">Save Changes</button>
            </div>
        </div>
    </div>
</div>


  <!-- Review Modal -->
  <div id="reviewModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
        <div class="px-6 py-4 border-b">
            <h5 class="text-lg font-semibold text-gray-800">Rate and Review</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 float-right close-modal">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>
        <div class="px-6 py-4">
            <form id="reviewForm">
                <!-- Hidden input for appointment ID -->
                <input type="hidden" id="appointmentId" value="">

                <!-- Star Rating -->
                <div class="mb-4">
                    <label for="rating" class="block text-sm font-medium text-gray-700">Rating:</label>
                    <div id="starRating" class="flex space-x-2 mt-2">
                        <span class="star text-gray-400 text-3xl cursor-pointer" data-value="1">&#9733;</span>
                        <span class="star text-gray-400 text-3xl cursor-pointer" data-value="2">&#9733;</span>
                        <span class="star text-gray-400 text-3xl cursor-pointer" data-value="3">&#9733;</span>
                        <span class="star text-gray-400 text-3xl cursor-pointer" data-value="4">&#9733;</span>
                        <span class="star text-gray-400 text-3xl cursor-pointer" data-value="5">&#9733;</span>
                    </div>
                    <input type="hidden" id="rating" name="rating" value="0">
                </div>

                <!-- Review Textarea -->
                <div class="mb-4">
                    <label for="review" class="block text-sm font-medium text-gray-700">Review:</label>
                    <textarea id="review" name="review" class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" rows="4" placeholder="Write your review here..."></textarea>
                </div>

                <!-- Submit Button -->
                <button type="button" id="submitReview" class="w-full py-2 text-white bg-primary hover:bg-primary/90 rounded-lg">
                    Submit Review
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
        cancelButton.style.display = 'none'; // Hide cancel button
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
    
    
    // Add event listener for the reschedule button
    document.getElementById('rescheduleButton').onclick = function () {
        // Show the reschedule modal
        document.getElementById('rescheduleModal').style.display = 'block';

        // Pre-fill the current date and time
        document.getElementById('newDate').value = data.date;
        document.getElementById('newTime').value = data.time;

        // Handle form submission
        document.getElementById('saveReschedule').onclick = function () {
    const newDate = document.getElementById('newDate').value;
    const newTime = document.getElementById('newTime').value;

    fetch(`/customer/appointments/reschedule/${data.id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ date: newDate, time: newTime }),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            alert(result.message); // Show success message

            // Close the reschedule modal
            document.getElementById('rescheduleModal').style.display = 'none';

            // Refresh the calendar dynamically
            location.reload();
        })
        .catch(error => {
            console.error('Error rescheduling appointment:', error);
            alert('Failed to reschedule appointment. Please try again.');
        });
};
    };
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
                    alert(result.message);
                    location.reload(); // Refresh the calendar
                })
                .catch(error => {
                    console.error('Error canceling appointment:', error);
                });
        }
    };

    // Show the appointment modal
    document.getElementById('appointmentModal').style.display = 'block';
}

function closeAllModals() {
    document.getElementById('appointmentModal').style.display = 'none';
    document.getElementById('rescheduleModal').style.display = 'none';
    document.getElementById('reviewModal').style.display = 'none';
}

document.querySelectorAll('.close-modal').forEach(button => {
    button.onclick = closeAllModals;
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
