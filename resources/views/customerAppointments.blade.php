<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Appointments</title>
    <link rel="stylesheet" href="{{ asset('css/customerAppointment.css') }}">
    <link rel ="stylesheet" href="{{asset ('css/customerHeader.css')}}" />
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
</head>
<body>

      @include('customer.customerHeader')

    <main>
    <div class="mb-4 max-w-7xl mx-auto px-4 md:px-8 pt-6">
        <a href="{{ route('customer.dashboard') }}">
            <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-600 bg-white border border-blue-600 hover:bg-blue-600 hover:text-white transition rounded-lg shadow-sm">
            <i class="ri-arrow-left-line text-lg"></i>
            </button>
        </a>
    </div>
   @include('successMessage')
        <h2 class="text-2xl font-semibold text-primary mb-2 text-center">Appointment Calendar</h2>
        <div id="calendar-container">
          <div id="calendar"></div>
      </div>

     <!-- Appointment Details Modal -->
<!-- Appointment Details Modal -->
<div id="appointmentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" style="display: none;">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="ri-calendar-line text-xl text-blue-500"></i>
                    </div>
                    <div>
                        <h5 class="text-xl font-semibold text-white">Appointment Details</h5>
                        <p class="text-blue-100 text-sm">View and manage your booking</p>
                    </div>
                </div>
                <button type="button" class="text-white hover:text-blue-200 transition-colors close-modal">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-200px)]">
            <!-- Freelancer Profile Section -->
            <div class="bg-gray-50 px-6 py-4 border-b">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gray-300 rounded-full overflow-hidden flex-shrink-0">
                        <img id="freelancerProfilePic" src="{{ asset('images/defaultprofile.jpg') }}" 
                            alt="Freelancer" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <h6 class="text-lg font-semibold text-gray-800" id="freelancerName">Loading...</h6>
                        <div class="flex items-center gap-2 text-sm text-gray-600 mt-1">
                            <i class="ri-star-fill text-yellow-400"></i>
                            <span id="freelancerRating">4.8</span>
                            <span class="text-gray-400">•</span>
                            <span id="freelancerReviews">124 reviews</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span id="appointmentStatus" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-white">
                            Status
                        </span>
                    </div>
                </div>
            </div>

            <!-- Appointment Information -->
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="ri-information-line text-blue-600"></i>
                                Booking Information
                            </h6>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3">
                                    <i class="ri-briefcase-line text-blue-600 mt-0.5"></i>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-700">Service:</span>
                                        <p class="text-sm text-gray-600 mt-1" id="appointmentSubservices">Loading...</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="ri-calendar-line text-blue-600"></i>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-700">Date:</span>
                                        <p class="text-sm text-gray-600" id="appointmentDate">Loading...</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i class="ri-time-line text-blue-600 mt-0.5"></i>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-700">Time:</span>
                                        <div id="appointmentTime" class="text-sm text-gray-600 mt-1">Loading...</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i class="ri-map-pin-line text-blue-600 mt-0.5"></i>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-700">Location:</span>
                                        <p class="text-sm text-gray-600 mt-1" id="appointmentAddress">Loading...</p>
                                    </div>
                                </div>
                                <div id="expiredInfo" class="bg-gray-100 text-red-700 p-3 text-sm rounded mb-3" style="display:none;">
                                    This appointment expired because it was not accepted before the scheduled time.
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="ri-sticky-note-line text-blue-600"></i>
                                Additional Notes
                            </h6>
                            <p class="text-sm text-gray-600 leading-relaxed" id="appointmentNotes">Loading...</p>
                        </div>

                        <!-- Decline Reason (Hidden by default) -->
                        <div id="declineReasonContainer" class="bg-red-50 border border-red-200 rounded-lg p-4" style="display: none;">
                            <h6 class="text-sm font-semibold text-red-700 mb-3 flex items-center gap-2">
                                <i class="ri-error-warning-line text-red-600"></i>
                                Reason for Decline
                            </h6>
                            <p class="text-sm text-red-600" id="declineReason">Loading...</p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <!-- Payment Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="ri-money-dollar-circle-line text-green-600"></i>
                                Payment Details
                            </h6>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Service Rate:</span>
                                    <span class="text-sm font-medium text-gray-800" id="serviceRate">Loading...</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Commitment Fee:</span>
                                    <span class="text-sm font-medium text-gray-800" id="feeStatus">Loading...</span>
                                </div>
                                <div id="finalPaymentContainer" class="flex justify-between items-center py-2 border-b border-gray-100" style="display: none;">
                                    <span class="text-sm text-gray-600">Final Payment:</span>
                                    <span class="text-sm font-medium text-gray-800" id="finalPaymentStatus">Loading...</span>
                                </div>
                                <div id="totalAmountContainer" class="flex justify-between items-center py-2 bg-green-50 rounded px-3" style="display: none;">
                                    <span class="text-sm font-semibold text-green-700">Total Amount:</span>
                                    <span class="text-lg font-bold text-green-700">₱<span id="totalAmount">0.00</span></span>
                                </div>
                            </div>
                        </div>

                     
                             <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="ri-information-line text-gray-600"></i>
                                Service Information
                            </h6>
                            <div class="space-y-2 text-sm text-gray-600">
                                <p><span class="font-medium">Contact:</span> <span id="appointmentContact">Loading...</span></p>
                                <p><span class="font-medium">Appointment ID:</span> #<span id="appointmentId2">Loading...</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row justify-end gap-3">
                <!-- Dynamic buttons - these will be shown/hidden based on appointment status -->
                <button id="rescheduleButton" class="btn bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-all duration-200">
                    <i class="ri-calendar-event-line"></i>
                    <span class="btn-text">Reschedule</span>
                    <span class="btn-spinner" style="display:none;">
                        <i class="ri-loader-4-line animate-spin"></i>
                    </span>
                </button>

                <button id="cancelButton" class="btn bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-all duration-200">
                    <i class="ri-close-circle-line"></i>
                    <span class="btn-text">Cancel</span>
                    <span class="btn-spinner" style="display:none;">
                        <i class="ri-loader-4-line animate-spin"></i>
                    </span>
                </button>

                <button id="rateButton" class="btn bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-all duration-200" style="display: none;">
                    <i class="ri-star-line"></i>
                    Rate & Review
                </button>

                <button id="payButton" class="btn bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-all duration-200" style="display: none;">
                    <i class="ri-bank-card-line"></i>
                    Pay Now
                </button>

                <button type="button" class="btn bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-all duration-200 close-modal">
                    <i class="ri-arrow-go-back-line"></i>
                    Close
                </button>

                <!-- Hidden No-Show Form -->
                <form id="noShowForm" method="POST" action="{{ route('appointments.no_show', 0) }}" style="display:none;">
                    @csrf
                    <button type="submit" class="btn bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-all duration-200">
                        <i class="ri-error-warning-line"></i>
                        <span class="btn-text">Mark as No-Show</span>
                        <span class="btn-spinner" style="display:none;">
                            <i class="ri-loader-4-line animate-spin"></i>
                        </span>
                    </button>
                </form>
            </div>
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
                <i class="ri-save-line"></i> 
               <span class="btn-text">Save Changes</span>
                <span class="btn-spinner" style="display:none;">
                    <i class="ri-loader-4-line animate-spin"></i>
                </span>
            </button>
        </div>
    </div>
</div>


<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <div class="flex items-center gap-2">
                <i class="ri-bank-card-line text-xl text-primary"></i>
                <h5 class="text-lg font-semibold text-gray-800">Complete Payment</h5>
            </div>
            <button type="button" class="text-gray-400 hover:text-gray-600 close-modal">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        <div class="px-6 py-4">
            <p class="mb-4 text-gray-700">Please enter the payment amount to complete the transaction for this service.</p>
            
            <div class="mb-4">
                <div id="appointmentSummary" class="bg-gray-50 p-4 rounded-lg mb-4">
                    <p><span class="font-medium">Freelancer:</span> <span id="summaryFreelancer"></span></p>
                    <p><span class="font-medium">Service:</span> <span id="summaryService"></span></p>
                    <p><span class="font-medium">Date:</span> <span id="summaryDate"></span></p>
                    <p><span class="font-medium">Time:</span> <span id="summaryTime"></span></p>
                    <p><span class="font-medium">Commitment Fee (Already Paid):</span> ₱<span id="summaryCommitmentFee">0.00</span></p>
                </div>
                
                <form id="paymentForm" action="{{ route('payment.final', ['id' => ':appointmentId']) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <div id="durationField" class="mb-4" style="display:none;">
                                <label id="durationLabel" class="block text-sm font-medium text-gray-700"></label>
                                <input type="number" id="durationInput" min="1" value="1"
                                    class="focus:ring-primary focus:border-primary block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
                            </div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount (₱)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            
                            <input type="number" name="amount" id="paymentAmount" step="0.01" min="0" 
                                class="focus:ring-primary focus:border-primary block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                placeholder="0.00" required>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">This is the total payment for the completed service.</p>
                    </div>
                    
                    <button type="submit" class="w-full py-2 text-white bg-primary hover:bg-primary/90 rounded-lg flex items-center justify-center gap-2">
                        <i class="ri-secure-payment-line"></i> 
                        <span class="btn-text">Process Payment</span>
                        <span class="btn-spinner" style="display:none;">
                            <i class="ri-loader-4-line animate-spin"></i>
                        </span>
                    </button>
                </form>
            </div>
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
                    <i class="ri-send-plane-line"></i> 
                   <span class="btn-text">Submit Review</span>
                    <span class="btn-spinner" style="display:none;">
                        <i class="ri-loader-4-line animate-spin"></i>
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Simple Custom Alert Container -->
<div id="customAlert" style="display:none;position:fixed;top:30px;right:30px;z-index:9999;min-width:260px;max-width:400px;"></div>

    </main>

    @include('customer.footer')
    <script>


      document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
        locale: 'en',
        height: window.innerWidth < 768 ? 'auto' : 600,
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
        },
         eventDidMount: function(info) {
    const status = info.event.extendedProps.status || 'pending';
    
    // Just add the data attribute for CSS styling and tooltip
    info.el.setAttribute('data-status', status.toLowerCase());
    info.el.title = `${info.event.title} - ${status}`;

    
  },
        // Responsive options
        windowResize: function(view) {
            if (window.innerWidth < 768) {
                if (calendar.view.type === 'dayGridMonth') {
                    calendar.changeView('listWeek');
                }
                calendar.setOption('height', 'auto');
            } else {
                calendar.setOption('height', 600);
            }
        },
         loading: function(bool) {
        const calendarEl = document.getElementById('calendar');
        if (bool) {
            calendarEl.classList.add('fc-loading');
        } else {
            calendarEl.classList.remove('fc-loading');
        }
    }
    });

    calendar.render();
});

function handleButtonVisibility(status) {
    const rescheduleButton = document.getElementById('rescheduleButton');
    const cancelButton = document.getElementById('cancelButton');
    const rateButton = document.getElementById('rateButton');
     const payButton = document.getElementById('payButton');
    // Hide or show buttons based on status
    rescheduleButton.style.display = 'inline-block';
    cancelButton.style.display = 'inline-block';
    rateButton.style.display = 'none';
      payButton.style.display = 'none';

    if (status.toLowerCase() === 'declined') {
        rescheduleButton.style.display = 'none'; // Hide reschedule button
        cancelButton.style.display = 'none'; // Hide cancel button
    } else if (status.toLowerCase() === 'completed') {
        rateButton.style.display = 'inline-block';
         
        if (currentAppointmentData && 
            (!currentAppointmentData.final_payment_status || 
             currentAppointmentData.final_payment_status === 'pending')) {
            payButton.style.display = 'inline-block';
         
        } 
        rescheduleButton.style.display = 'none'; // Hide reschedule button
        cancelButton.style.display = 'none'; // Hide cancel button
    } else if (status.toLowerCase() === 'accepted') {
        rescheduleButton.style.display = 'none'; // Hide reschedule button
        cancelButton.style.display = 'inline-block'; // Hide cancel button
     } else if (status.toLowerCase() === 'cancelled') {
        // Hide both buttons if status is cancelled
        rescheduleButton.style.display = 'none';
        cancelButton.style.display = 'none';
      } else if (status.toLowerCase() === 'expired') {
      
        rescheduleButton.style.display = 'none';
        cancelButton.style.display = 'none';
        rateButton.style.display = 'none';
        payButton.style.display = 'none';
     
    } else {
        // Default to showing both reschedule and cancel buttons for other statuses
        rescheduleButton.style.display = 'inline-block';
        cancelButton.style.display = 'inline-block';
    }
}

function formatTo12Hour(timeStr) {
    try {
        // Handle various time formats
        let parts = timeStr.split(':');
        let hour = parseInt(parts[0], 10);
        let minute = parts.length > 1 ? parseInt(parts[1], 10) : 0;
        
        const period = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12; // Convert 0 to 12 for midnight
        
        // Format with leading zeros for minutes
        return `${hour12}:${minute.toString().padStart(2, '0')} ${period}`;
    } catch (error) {
        console.error("Error in formatTo12Hour:", error, timeStr);
        return timeStr; // Return original string if parsing fails
    }
}

function getStatusColorFromBackend(status) {
    const colorMap = {
        'pending': '#fbbf24',           // Yellow
        'accepted': '#2563eb',          // Blue  
        'completed': '#10b981',         // Green
        'canceled': '#ef4444',          // Red
        'cancelled': '#ef4444',         // Red (alternative spelling)
        'declined': '#6b7280',          // Gray
        'no_show_freelancer': '#eab308', // Amber/Orange
        'no_show_customer': '#a21caf',   // Purple
        'rescheduled': '#8b5cf6',        // Purple for rescheduled status
           'expired': '#9ca3af'
    };
    
    return colorMap[status] || '#6b7280'; // Default Gray
}

function formatTimeRange(startTime, durationMinutes) {
    if (!startTime) return 'N/A';
    
    console.log("Formatting time range:", startTime, "Duration:", durationMinutes); // Debug
      
   
    try {
        // Parse the start time (expecting format like "09:00" or "14:30")
        const [hours, minutes] = startTime.split(':').map(Number);
        
        // Create start date object (using a dummy date)
        const startDate = new Date(2000, 0, 1, hours, minutes);
        
        // Create end date by adding duration
        const endDate = new Date(startDate.getTime() + (durationMinutes * 60 * 1000));
        
        // Format both times in 12-hour format
        const startFormatted = formatTo12Hour(startTime);
        
        // Format end time with proper minutes
        const endHours = endDate.getHours();
        const endMinutes = endDate.getMinutes();
        const endTimeStr = `${endHours}:${endMinutes.toString().padStart(2, '0')}`;
        const endFormatted = formatTo12Hour(endTimeStr);
        
        console.log("Calculated end time:", endTimeStr, "Formatted:", endFormatted); // Debug
        
        return `${startFormatted} - ${endFormatted}`;
    } catch (error) {
        console.error("Error in formatTimeRange:", error);
        return `${startTime} - Error`;
    }
}
// Add a variable to store current appointment data
let currentAppointmentData = null;

function openAppointmentModal(data) {
    // Populate modal with appointment details
    console.log('Full appointment data:', data);
    console.log('Decline Reason:', data.decline_reason); // Debugging
    document.getElementById('freelancerName').textContent = data.freelancer_name || 'N/A';
       const profilePic = document.getElementById('freelancerProfilePic');
    if (data.freelancer_profile_picture) {
        profilePic.src = `{{ asset('storage') }}/${data.freelancer_profile_picture}`;
        profilePic.onerror = function() {
            // Fallback to default image if the profile picture fails to load
            this.src = "{{ asset('images/defaultprofile.png') }}";
        };
    } else {
        profilePic.src = "{{ asset('images/defaultprofile.png') }}";
    }
    document.getElementById('appointmentSubservices').textContent =
    data.services_list || 'N/A';
    document.getElementById('appointmentDate').textContent = data.date || 'N/A';
 
    document.getElementById('appointmentAddress').textContent = data.address || 'N/A';
      // Enhanced status display with dynamic color from backend
    const statusElement = document.getElementById('appointmentStatus');
    const status = data.status || 'N/A';
    statusElement.textContent = status;
    statusElement.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-white';
    const statusColor = getStatusColorFromBackend(status.toLowerCase());
    statusElement.style.backgroundColor = statusColor;

    const expiredInfo = document.getElementById('expiredInfo');
        if (data.status && data.status.toLowerCase() === 'expired') {
            expiredInfo.style.display = 'block';
        } else {
            expiredInfo.style.display = 'none';
        }



    document.getElementById('appointmentNotes').textContent = data.notes || 'No additional notes';
    document.getElementById('feeStatus').textContent = data.fee_status || 'N/A';
     document.getElementById('appointmentContact').textContent = data.contact || 'Not provided';
    document.getElementById('appointmentId2').textContent = data.id || 'N/A';
    document.getElementById('serviceRate').textContent = data.rate_type && data.rate
        ? `₱${parseFloat(data.rate).toFixed(2)} per ${data.rate_type}`
        : 'N/A';
    document.getElementById('appointmentId').value = data.id;
    
    currentAppointmentData = data;
    // Show the decline reason if the status is "declined"
    const declineReasonContainer = document.getElementById('declineReasonContainer');
    const declineReason = document.getElementById('declineReason');
    
    if (data.status.toLowerCase() === 'declined') {
        declineReasonContainer.style.display = 'block';
        declineReason.textContent = data.decline_reason ? data.decline_reason : 'No reason provided';
    } else {
        declineReasonContainer.style.display = 'none';
    }

     const finalPaymentContainer = document.getElementById('finalPaymentContainer');
    const finalPaymentStatus = document.getElementById('finalPaymentStatus');
    const totalAmountContainer = document.getElementById('totalAmountContainer');
    const totalAmount = document.getElementById('totalAmount');
    
    if (data.status.toLowerCase() === 'completed') {
        finalPaymentContainer.style.display = 'flex';
        finalPaymentStatus.textContent = data.final_payment_status || 'pending';
        
        if (data.final_payment_status === 'paid' && data.total_amount) {
            totalAmountContainer.style.display = 'flex';
            totalAmount.textContent = parseFloat(data.total_amount).toFixed(2);
        } else {
            totalAmountContainer.style.display = 'none';
        }
    } else {
        finalPaymentContainer.style.display = 'none';
        totalAmountContainer.style.display = 'none';
    }
    
     console.log("Debug - Appointment Duration:", { 
        time: data.time, 
        duration: data.duration,
        bufferTime: data.post ? data.post.buffer_time : 0 
    });
    
   const duration = data.duration || (data.post ? data.post.service_duration : 60);
    const bufferTime = data.post ? data.post.buffer_time : 0;
    
    // Display time range with buffer information
    let timeDisplay = formatTimeRange(data.time, duration);
    
    // Add buffer time info if present
    if (bufferTime > 0) {
        timeDisplay += `<span class="text-xs text-gray-500 block">${duration} minute service + ${bufferTime} minute buffer</span>`;
    } else {
        timeDisplay += `<span class="text-xs text-gray-500 block">${duration} minute service</span>`;
    }
    
    document.getElementById('appointmentTime').innerHTML = timeDisplay;

    
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
           const today = new Date();
                const appointmentDate = new Date(currentAppointmentData.date); // Use the appointment date from modal data

                const uniqueDates = [...new Set(
                    availabilities
                        .map(a => a.date)
                        .filter(dateStr => {
                            const d = new Date(dateStr);
                            // Show if date is today or in the future, OR if it's the appointment date and not in the past
                            return d >= today || d.getTime() === appointmentDate.getTime();
                        })
                )];
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
    const timesContainer = document.getElementById('availableTimes');
    timesContainer.innerHTML = '<div class="col-span-3 text-center py-2">Loading times...</div>';
    
    const dateAvailability = availabilities.find(a => a.date === selectedDate);
    
    if (!dateAvailability) {
        timesContainer.innerHTML = '<div class="col-span-3 text-center py-2">No times available</div>';
        return;
    }
    
    console.log("Date availability found:", dateAvailability); // Debug
    
    // Parse start and end times from 12-hour format
    let startHour, endHour;
    
    try {
        // Parse start time from 12-hour format
        const startMatch = dateAvailability.start_time.match(/(\d+):(\d+)\s*(AM|PM)/i);
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
        
        // Parse end time from 12-hour format
        const endMatch = dateAvailability.end_time.match(/(\d+):(\d+)\s*(AM|PM)/i);
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
    } catch (error) {
        console.error("Error parsing time format:", error);
        timesContainer.innerHTML = '<div class="col-span-3 text-center py-2">Error loading time slots</div>';
        return;
    }
    
    const bookedTimes = dateAvailability.booked_times || [];
    console.log(`Rendering time slots from ${startHour}:00 to ${endHour}:00`);
    console.log("Booked times:", bookedTimes);
    
    timesContainer.innerHTML = '';
    let hasAvailableTimes = false;
    
    // Generate 1-hour time slots
    for (let h = startHour; h < endHour; h++) {
        const timeStr = `${h.toString().padStart(2, '0')}:00`;
        
        // Check if this time is booked
        const isBooked = bookedTimes.some(bookedTime => {
            // Handle different possible formats
            if (typeof bookedTime === 'string') {
                return bookedTime === timeStr || 
                       bookedTime.startsWith(timeStr + ':') ||
                       bookedTime.includes(convertTo12HourFormat(h));
            }
            return false;
        });
        
        // Create button for this time slot
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = isBooked 
            ? 'time-btn px-3 py-2 border rounded bg-gray-200 text-gray-500 cursor-not-allowed'
            : 'time-btn px-3 py-2 border rounded';
        
        // Format time for display (12-hour format)
        const timeDisplay = convertTo12HourFormat(h);
        btn.textContent = timeDisplay + (isBooked ? ' (Booked)' : '');
        btn.dataset.time = timeStr;
        btn.disabled = isBooked;
        
         if (isBooked) {
        btn.title = "This time slot is already booked";
    }

        if (!isBooked) {
            hasAvailableTimes = true;
            btn.addEventListener('click', function() {
                // Clear previous selections
                document.querySelectorAll('#availableTimes .time-btn').forEach(b => {
                    b.classList.remove('bg-primary', 'text-white');
                });
                
                // Mark this time as selected
                this.classList.add('bg-primary', 'text-white');
                
                // Store selected time
                document.getElementById('selectedTime').value = this.dataset.time;
            });
        }
        
        timesContainer.appendChild(btn);
    }
    
    if (!hasAvailableTimes) {
        timesContainer.innerHTML = '<div class="col-span-3 text-center py-2">No available times for this date</div>';
    }
}

// Helper function to convert hour to 12-hour format string
function convertTo12HourFormat(hour) {
    const period = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour % 12 || 12; // Convert 0 to 12 for midnight
    return `${hour12}:00 ${period}`;
}


// Handle form submission
    document.getElementById('saveReschedule').onclick = function(e) {
        e.preventDefault();
          const btn = this;
        showSpinnerOnButton(btn);
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
            restoreButton(btn, 'Save Changes');
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
        const btn = this;
        if (confirm('Are you sure you want to cancel this appointment?')) {
            showSpinnerOnButton(btn);
            fetch(`/customer/appointments/cancel/${data.id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(result => {
                    restoreButton(btn, 'Cancel');
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
    document.getElementById('paymentModal').style.display = 'none';
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
             const btn = this;
             showSpinnerOnButton(btn);
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
                restoreButton(btn, 'Submit Review');
                console.log(result);
               showCustomAlert('Review submitted successfully!', 'success');
                reviewModal.style.display = 'none'; // Close the review modal
                location.reload(); // Refresh the page to reflect the review
            })
            .catch(error => {
                console.error('Error submitting review:', error);
                showCustomAlert('Failed to submit review. Please try again.', 'error');
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

document.addEventListener('DOMContentLoaded', function () {
    const appointmentModal = document.getElementById('appointmentModal');
    const paymentModal = document.getElementById('paymentModal');
    const payButton = document.getElementById('payButton');
    const paymentForm = document.getElementById('paymentForm');
    
    // Open Payment Modal when "Pay Now" button is clicked
    if (payButton) {
        payButton.addEventListener('click', function() {
            // Hide appointment modal
            appointmentModal.style.display = 'none';
            
            // Get current appointment ID
            const appointmentId = currentAppointmentData?.id;
            
            if (!appointmentId) {
                alert('Error: Cannot process payment. Appointment data is missing.');
                return;
            }
            
            // Update form action URL with correct appointment ID
            const formAction = paymentForm.getAttribute('action').replace(':appointmentId', appointmentId);
            paymentForm.setAttribute('action', formAction);
            
            // Populate payment summary
            document.getElementById('summaryFreelancer').textContent = document.getElementById('freelancerName').textContent;
            document.getElementById('summaryService').textContent = currentAppointmentData?.services_list || 'Service';
            document.getElementById('summaryDate').textContent = document.getElementById('appointmentDate').textContent;
            document.getElementById('summaryTime').textContent = document.getElementById('appointmentTime').textContent;
            
            // Get commitment fee from data and format it properly
            const commitmentFee = currentAppointmentData?.commitment_fee || '0.00';
            document.getElementById('summaryCommitmentFee').textContent = parseFloat(commitmentFee).toFixed(2);
            
            // Reset payment amount field
            document.getElementById('paymentAmount').value = '';
            
            // Show duration field for hourly/daily rate
                
                const rateType = currentAppointmentData?.rate_type || '';
                const rate = parseFloat(currentAppointmentData?.rate || '0');
                const durationField = document.getElementById('durationField');
                const durationLabel = document.getElementById('durationLabel');
                const durationInput = document.getElementById('durationInput');
                const paymentAmount = document.getElementById('paymentAmount');

                if (rateType === 'hourly') {
                    durationField.style.display = 'block';
                    durationLabel.textContent = 'Number of Hours';
                    durationInput.value = 1;
                } else if (rateType === 'daily') {
                    durationField.style.display = 'block';
                    durationLabel.textContent = 'Number of Days';
                    durationInput.value = 1;
                } else {
                    durationField.style.display = 'none';
                    durationInput.value = 1;
                }

                // Calculate and update amount when duration changes
                function updateAmount() {
                    let duration = parseInt(durationInput.value) || 1;
                    let total = rate * duration;

                   let finalAmount = total - commitmentFee;
                    if (finalAmount < 0) finalAmount = 0;
                    paymentAmount.value = finalAmount.toFixed(2);
                }

                durationInput.oninput = updateAmount;
                updateAmount(); // Initial calculation

                // Make payment amount read-only
                paymentAmount.readOnly = true;


            // Show payment modal
            paymentModal.style.display = 'flex';
        });
    }
    
    // Add close modal functionality
    paymentModal.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function() {
            paymentModal.style.display = 'none';
        });
    });
    
    // Handle payment form submission - CHOOSE ONE APPROACH:
    
    // OPTION 1: Let the form submit naturally to the route (recommended)
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
          
            // Validate amount before submission
            const amount = document.getElementById('paymentAmount').value;
            if (isNaN(parseFloat(amount)) || parseFloat(amount) <= 0) {
                e.preventDefault();
                showCustomAlert('Please enter a valid payment amount.', 'error');
                return;
            }
            
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Processing...';
            
            // Form will submit naturally to the route specified in the action attribute
        });
    }
})  

 


// Improve mobile modal handling
function adjustModalForMobile() {
    const modals = [
        document.getElementById('appointmentModal'), 
        document.getElementById('rescheduleModal'),
        document.getElementById('reviewModal'),
        document.getElementById('paymentModal')
    ];
    
    modals.forEach(modal => {
        if (!modal) return;
        
        const modalContent = modal.querySelector('.bg-white');
        
        if (window.innerWidth <= 576) {
            modalContent.style.width = 'calc(100% - 20px)';
            modalContent.style.maxHeight = '90vh';
        } else {
            modalContent.style.width = '';
            modalContent.style.maxHeight = '';
        }
    });
}

// Call the function on window resize
window.addEventListener('resize', adjustModalForMobile);

// Call once on page load
document.addEventListener('DOMContentLoaded', function() {
    adjustModalForMobile();
});

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
function showCustomAlert(message, type = 'success', duration = 5000) {
    const alertBox = document.getElementById('customAlert');
    if (!alertBox) return;

    // Use Boxicons and match session alert colors
    let icon = '', borderColor = '', bgColor = '', textColor = '';
    if (type === 'success') {
        icon = "<i class='bx bx-check-circle'></i>";
        borderColor = '#10b981';
        bgColor = '#ecfdf5';
        textColor = '#047857';
    } else if (type === 'error') {
        icon = "<i class='bx bx-error-circle'></i>";
        borderColor = '#ef4444';
        bgColor = '#fef2f2';
        textColor = '#b91c1c';
    } else if (type === 'warning') {
        icon = "<i class='bx bx-error'></i>";
        borderColor = '#f59e0b';
        bgColor = '#fef9c3';
        textColor = '#b45309';
    } else {
        icon = "<i class='bx bx-info-circle'></i>";
        borderColor = '#2563eb';
        bgColor = '#eff6ff';
        textColor = '#2563eb';
    }

    alertBox.innerHTML = `
        <div style="
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 15px 25px;
            border-left: 4px solid ${borderColor};
            background-color: ${bgColor};
            color: ${textColor};
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 1;
            transition: opacity 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 400px;
        ">
            <span style="display:flex;align-items:center;">
                ${icon}
                <span style="margin-left:8px;">${message}</span>
            </span>
            <button type="button" class="close-btn" onclick="document.getElementById('customAlert').style.display='none';"
                style="background: transparent; border: none; color: ${textColor}; font-size: 1.2em; cursor: pointer; margin-left: 15px; padding: 0; line-height: 1;">&times;</button>
        </div>
    `;
    alertBox.style.display = 'block';

    setTimeout(() => {
        alertBox.style.display = 'none';
    }, duration);
}

   

</script>
 <!-- FullCalendar JS -->
 <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
</body>
</html>
