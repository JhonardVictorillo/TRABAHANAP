 

   <!-- Appointment Calendar -->
   <section class="appointment-calendar" id="appointmentCalendar" style="display: none;">
      <div class="text-center  text-3xl font-bold mb-5">Appointment Calendar</div>
      
      <!-- Filter Bar -->
      <!-- <div class="flex justify-center gap-3 mb-5">
          <button class="filter-btn bg-green-500 text-white">Completed</button>
          <button class="filter-btn bg-blue-500 text-white">Scheduled</button>
          <button class="filter-btn bg-yellow-500 text-white">Waiting</button>
          <button class="filter-btn bg-red-500 text-white">Canceled</button>
          <button class="filter-btn bg-gray-500 text-white">Show All</button>
      </div> -->

      <!-- Calendar Container -->
      <div id="calendar-container">
          <div id="calendar"></div>
      </div>
    </section>
    

<!-- Update only the modal design - replace the existing modal div -->
<div id="appointmentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" style="display: none;">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-check text-xl text-white"></i>
                    </div>
                    <div>
                        <h5 class="text-xl font-semibold text-white">Appointment Details</h5>
                        <p class="text-blue-100 text-sm">Manage your booking</p>
                    </div>
                </div>
                <button id="closeAppointmentModal" type="button" class="text-white hover:text-blue-200 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-200px)]">
            <!-- Customer Profile Section -->
            <div class="bg-gray-50 px-6 py-4 border-b">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gray-300 rounded-full overflow-hidden flex-shrink-0">
                        <img id="customerProfilePic" src="{{ asset('images/defaultprofile.jpg') }}" 
                             alt="Customer" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <h6 class="text-lg font-semibold text-gray-800" id="appointmentName">Loading...</h6>
                        <div class="flex items-center gap-2 text-sm text-gray-600 mt-1">
                            <i class="fas fa-phone text-blue-600"></i>
                            <span id="appointmentContact">Loading...</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span id="appointmentStatusBadge" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-white">
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
                                <i class="fas fa-info-circle text-blue-600"></i>
                                Booking Information
                            </h6>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-briefcase text-blue-600 mt-0.5"></i>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-700">Service:</span>
                                        <p class="text-sm text-gray-600 mt-1" id="appointmentSubservices">Loading...</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-calendar-alt text-blue-600"></i>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-700">Date:</span>
                                        <p class="text-sm text-gray-600" id="appointmentDate">Loading...</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-clock text-blue-600 mt-0.5"></i>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-700">Time:</span>
                                        <div id="appointmentTime" class="text-sm text-gray-600 mt-1">Loading...</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-map-marker-alt text-blue-600 mt-0.5"></i>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-700">Location:</span>
                                        <p class="text-sm text-gray-600 mt-1" id="appointmentAddress">Loading...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-sticky-note text-blue-600"></i>
                                Customer Notes
                            </h6>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-sm text-gray-600 leading-relaxed" id="appointmentNotes">Loading...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <!-- Payment Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-dollar-sign text-green-600"></i>
                                Payment Details
                            </h6>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Payment Status:</span>
                                    <span class="text-sm font-medium" id="appointmentPaymentStatus">Loading...</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Amount:</span>
                                    <span class="text-sm font-medium text-gray-800" id="appointmentPaymentAmount">Loading...</span>
                                </div>
                                <div class="flex justify-between items-center py-2 bg-green-50 rounded px-3">
                                    <span class="text-sm font-semibold text-green-700">Total Service Fee:</span>
                                    <span class="text-lg font-bold text-green-700" id="totalServiceFee">₱0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Service Information -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-cog text-gray-600"></i>
                                Service Information
                            </h6>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Duration:</span>
                                    <span id="serviceDuration">60 minutes</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Appointment ID:</span>
                                    <span id="appointmentId">#Loading...</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Current Status:</span>
                                    <span id="appointmentStatus" class="font-medium">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row justify-end gap-3">
                <!-- Accept Button -->
                <button id="acceptAppointmentBtn" class="btn bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-all duration-200" data-id="">
                    <i class="fas fa-check"></i>
                    <span class="btn-text">Accept</span>
                    <span class="btn-spinner" style="display:none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </button>

                <!-- Decline Button -->
                <button id="declineAppointmentBtn" class="btn bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-all duration-200" data-id="">
                    <i class="fas fa-times"></i>
                    <span class="btn-text">Decline</span>
                    <span class="btn-spinner" style="display:none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </button>

                <!-- Complete Button -->
                <button id="completeAppointmentBtn" class="btn bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-all duration-200" data-id="">
                    <i class="fas fa-check-double"></i>
                    <span class="btn-text">Mark Complete</span>
                    <span class="btn-spinner" style="display:none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </button>
            </div>
        </div>

        <!-- Decline Reason Container -->
        <div id="declineReasonContainer" style="display:none;" class="px-6 py-4 border-t border-gray-200 bg-red-50">
            <div class="mb-4">
                <label for="declineReason" class="block text-sm font-medium text-red-700 mb-2 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                    Reason for Declining:
                </label>
                <textarea 
                    id="declineReason" 
                    placeholder="Please provide a reason for declining this appointment..." 
                    rows="3" 
                    class="w-full px-3 py-2 border border-red-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                ></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button id="confirmDeclineBtn" class="btn bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all duration-200">
                    <i class="fas fa-check"></i>
                    <span class="btn-text">Confirm Decline</span>
                    <span class="btn-spinner" style="display:none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </button>
                <button id="cancelDeclineBtn" class="btn bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all duration-200">
                    <i class="fas fa-arrow-left"></i>
                    Cancel
                </button>
            </div>
        </div>

        <!-- No-Show Form -->
        <form id="noShowForm" method="POST" action="{{ route('appointments.no_show', 0) }}" style="display:none;" class="px-6 py-4 border-t border-gray-200 bg-yellow-50">
            @csrf
            <div class="text-center mb-4">
                <h6 class="text-sm font-semibold text-yellow-700 mb-2 flex items-center justify-center gap-2">
                    <i class="fas fa-user-times text-yellow-600"></i>
                    Customer No-Show
                </h6>
                <p class="text-xs text-yellow-600">The appointment time has passed. Mark customer as no-show if they didn't arrive.</p>
            </div>
            <button type="submit" class="btn flex items-center gap-2 w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg justify-center transition-all duration-200">
                <i class="fas fa-user-slash"></i>
                <span class="btn-text">Mark Customer as No-Show</span>
                <span class="btn-spinner" style="display:none;">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </button>
        </form>
    </div>
</div>

<script>
 document.addEventListener('DOMContentLoaded', function() {
    let calendar;
    let calendarInitialized = false;
    

    // Add the missing utility functions at the top
    function showSpinnerOnButton(button) {
        if (!button) return;
        const btnText = button.querySelector('.btn-text');
        const btnSpinner = button.querySelector('.btn-spinner');
        
        if (btnText) btnText.style.display = 'none';
        if (btnSpinner) btnSpinner.style.display = 'inline-block';
        
        button.disabled = true;
    }

    function restoreButton(button, originalText) {
        if (!button) return;
        const btnText = button.querySelector('.btn-text');
        const btnSpinner = button.querySelector('.btn-spinner');
        
        if (btnText) {
            btnText.style.display = 'inline-block';
            btnText.textContent = originalText;
        }
        if (btnSpinner) btnSpinner.style.display = 'none';
        
        button.disabled = false;
    }

    // Helper functions
    function to24HourTime(timeStr) {
        if (!timeStr) return '00:00:00';
        
        // If already in 24-hour format (HH:MM), return as is with seconds
        if (/^\d{1,2}:\d{2}$/.test(timeStr)) {
            return timeStr + ':00';
        }
        
        // If in 12-hour format (H:MM AM/PM), convert it
        if (timeStr.includes(' ')) {
            const [time, modifier] = timeStr.split(' ');
            let [hours, minutes] = time.split(':');
            if (hours === '12') hours = '00';
            if (modifier === 'PM') hours = parseInt(hours, 10) + 12;
            return `${hours.toString().padStart(2, '0')}:${minutes}:00`;
        }
        
        // Fallback
        return timeStr;
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
        'rescheduled': '#8b5cf6'        // Purple for rescheduled status
    };
    
    return colorMap[status] || '#6b7280'; // Default Gray
}

    function formatTo12Hour(timeStr) {
        try {
            let parts = timeStr.split(':');
            let hour = parseInt(parts[0], 10);
            let minute = parts.length > 1 ? parseInt(parts[1], 10) : 0;
            
            const period = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            
            return `${hour12}:${minute.toString().padStart(2, '0')} ${period}`;
        } catch (error) {
            console.error("Error in formatTo12Hour:", error, timeStr);
            return timeStr;
        }
    }

    function formatTimeRange(startTime, durationMinutes) {
        if (!startTime) return 'N/A';
        
        try {
            const [hours, minutes] = startTime.split(':').map(Number);
            const startDate = new Date(2000, 0, 1, hours, minutes);
            const endDate = new Date(startDate.getTime() + (durationMinutes * 60 * 1000));
            
            const startFormatted = formatTo12Hour(startTime);
            const endHours = endDate.getHours();
            const endMinutes = endDate.getMinutes();
            const endTimeStr = `${endHours}:${endMinutes.toString().padStart(2, '0')}`;
            const endFormatted = formatTo12Hour(endTimeStr);
            
            return `${startFormatted} - ${endFormatted}`;
        } catch (error) {
            console.error("Error in formatTimeRange:", error);
            return `${startTime} - Error`;
        }
    }

    // Initialize FullCalendar
  // Expose the initializeCalendar function globally
    window.initializeCalendar = function() {
        if (calendarInitialized && window.calendar) {
            console.log('Calendar already initialized, just rendering...');
            window.calendar.render();
            window.calendar.updateSize();
            return;
        }

        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) {
            console.error('Calendar element not found');
            return;
        }

        // Check if FullCalendar is loaded
        if (typeof FullCalendar === 'undefined') {
            console.error('FullCalendar library not loaded');
            return;
        }

        console.log('Initializing calendar...');
        
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'en',
            height: 600,
            selectable: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            events: '/freelancer/appointments',
            // Updated eventContent for better presentation
   eventContent: function(arg) {
   const event = arg.event;
    const status = event.extendedProps.status ? event.extendedProps.status.toLowerCase() : 'pending';
    const customerName = event.title || 'Unknown Customer';

    // Get the proper time range instead of abbreviated time
    let timeDisplay = 'No time';
    
    // Check if we have extended properties with duration
    if (event.extendedProps && event.extendedProps.time && event.extendedProps.duration) {
        timeDisplay = formatTimeRange(event.extendedProps.time, event.extendedProps.duration);
    } else if (event.start && event.end) {
        // If we have start and end times from FullCalendar
        const startTime = event.start.toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit', 
            hour12: true 
        });
        const endTime = event.end.toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit', 
            hour12: true 
        });
        timeDisplay = `${startTime} - ${endTime}`;
    } else if (event.extendedProps && event.extendedProps.time) {
        // If we only have start time, format it properly
        timeDisplay = formatTo12Hour(event.extendedProps.time);
    }
    
    // Use colors directly from the backend
    const backgroundColor = event.backgroundColor || '#9ca3af';
    const borderColor = event.borderColor || '#6b7280';
    
    return {
        html: `
            <div class="fc-event-clean" style="
                background-color: ${backgroundColor} !important;
                color: white !important;
                border-radius: 8px !important;
                padding: 8px !important;
                border-left: 4px solid ${borderColor} !important;
                box-shadow: 0 1px 3px rgba(0,0,0,0.12) !important;
                cursor: pointer !important;
                overflow: hidden !important;
                transition: all 0.2s ease !important;
                margin: 2px 0 !important;
                min-height: 45px !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: center !important;
                border: none !important;
            ">
                <div style="
                    font-size: 11px !important;
                    font-weight: 600 !important;
                    margin-bottom: 4px !important;
                    opacity: 0.9 !important;
                    line-height: 1.2 !important;
                ">
                    ${timeDisplay}
                </div>
                <div style="
                    font-size: 13px !important;
                    font-weight: 500 !important;
                    line-height: 1.3 !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    white-space: nowrap !important;
                ">
                    ${customerName}
                </div>
            </div>
        `
    };
},
    
    // Enhanced event styling
    // Enhanced event rendering with status colors
    eventDidMount: function(info) {
        const status = info.event.extendedProps.status || 'pending';
        info.el.setAttribute('data-status', status.toLowerCase());
        
        // Add enhanced tooltip
        info.el.title = `${info.event.title} - Status: ${status.charAt(0).toUpperCase() + status.slice(1)}`;
        
        // Add hover effects
        info.el.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-1px)';
            this.style.zIndex = '10';
        });
        
        info.el.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.zIndex = '1';
        });
    },
            eventClick: function (info) {
                const eventId = info.event.id;
                console.log('Clicked Event ID:', eventId);

                fetch(`/freelancer/appointments/${eventId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Data fetched:', data);
                        openAppointmentModal(data, eventId);
                    })
                    .catch(error => {
                        console.error('Error fetching details:', error);
                    });
            },
             // Responsive handling
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
            
            // Loading state
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
        calendarInitialized = true;
        console.log('Calendar initialized successfully');
    }

    // Listen for calendar initialization event
    window.addEventListener('initializeCalendar', initializeCalendar);

    // Initialize calendar if appointment section is visible on page load
    const appointmentSection = document.getElementById('appointmentCalendar');
    if (appointmentSection && appointmentSection.style.display !== 'none') {
        setTimeout(initializeCalendar, 100);
    }

    // Modal elements
    const appointmentModal = document.getElementById('appointmentModal');
    const closeAppointmentModal = document.getElementById('closeAppointmentModal');
    const acceptBtn = document.getElementById('acceptAppointmentBtn');
    const declineBtn = document.getElementById('declineAppointmentBtn');
    const completeBtn = document.getElementById('completeAppointmentBtn');
    const declineReasonContainer = document.getElementById('declineReasonContainer');
    const confirmDeclineBtn = document.getElementById('confirmDeclineBtn');
    const cancelDeclineBtn = document.getElementById('cancelDeclineBtn');

    // Modal functions
    function openAppointmentModal(data, eventId) {
        console.log('Appointment data:', data);

        if (!appointmentModal) {
            console.error('Appointment modal not found');
            return;
        }

        // Get modal elements with null checks
        const appointmentNameEl = document.getElementById('appointmentName');
        const appointmentDateEl = document.getElementById('appointmentDate');
        const appointmentTimeEl = document.getElementById('appointmentTime');
        const appointmentAddressEl = document.getElementById('appointmentAddress');
        const appointmentContactEl = document.getElementById('appointmentContact');
        const appointmentStatusEl = document.getElementById('appointmentStatus');
        const appointmentNotesEl = document.getElementById('appointmentNotes');
        const appointmentSubservicesEl = document.getElementById('appointmentSubservices');
        const appointmentPaymentStatusEl = document.getElementById('appointmentPaymentStatus');
        const appointmentPaymentAmountEl = document.getElementById('appointmentPaymentAmount');

        if (!appointmentNameEl || !appointmentDateEl || !appointmentTimeEl) {
            console.error('Required modal elements not found');
            return;
        }

        // Populate modal elements
        appointmentNameEl.textContent = data.name || 'N/A';
        appointmentDateEl.textContent = data.date || 'N/A';
        
        // Format time display with duration
        const duration = data.duration || 60;
        let timeDisplay = formatTimeRange(data.time, duration);
        timeDisplay += `<span class="text-xs text-gray-500 block mt-1">${duration} minute service</span>`;
        appointmentTimeEl.innerHTML = timeDisplay;
        
        if (appointmentAddressEl) appointmentAddressEl.textContent = data.address || 'N/A';
        if (appointmentContactEl) appointmentContactEl.textContent = data.contact || 'N/A';
        if (appointmentStatusEl) appointmentStatusEl.textContent = data.status || 'N/A';
        if (appointmentNotesEl) appointmentNotesEl.textContent = data.notes || 'None';
        
        if (appointmentSubservicesEl) {
            appointmentSubservicesEl.textContent = Array.isArray(data.subservices) && data.subservices.length
                ? data.subservices.join(', ')
                : 'N/A';
        }

         // Set customer profile picture and information
    const customerProfilePic = document.getElementById('customerProfilePic');
    if (customerProfilePic) {
        if (data.customer_profile_picture) {
            customerProfilePic.src = `{{ asset('storage') }}/${data.customer_profile_picture}`;
            customerProfilePic.onerror = function() {
                // Fallback to default image if the profile picture fails to load
                this.src = "{{ asset('images/defaultprofile.jpg') }}";
            };
        } else {
            customerProfilePic.src = "{{ asset('images/defaultprofile.jpg') }}";
        }
    }

  const appointmentStatusBadgeEl = document.getElementById('appointmentStatusBadge');
    if (appointmentStatusBadgeEl) {
        const status = data.status || 'N/A';
        appointmentStatusBadgeEl.textContent = status;
        appointmentStatusBadgeEl.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-white';
        appointmentStatusBadgeEl.style.backgroundColor = getStatusColorFromBackend(status.toLowerCase());
    }
    
    // Add service information elements
    const serviceDurationEl = document.getElementById('serviceDuration');
    const appointmentIdEl = document.getElementById('appointmentId');
    const totalServiceFeeEl = document.getElementById('totalServiceFee');
    
    if (serviceDurationEl) serviceDurationEl.textContent = `${duration} minutes`;
    if (appointmentIdEl) appointmentIdEl.textContent = `#${data.id || 'N/A'}`;

     // Handle total service fee
    if (totalServiceFeeEl) {
        if ((data.final_payment_status === 'paid' || data.final_payment_status === 'paid_cash') && data.total_amount) {
            totalServiceFeeEl.textContent = `₱${parseFloat(data.total_amount).toFixed(2)}`;
        } else {
            totalServiceFeeEl.textContent = `₱${parseFloat(data.total_amount || 0).toFixed(2)}`;
        }
    }

        // Handle payment status
        if (appointmentPaymentStatusEl) {
            if (data.status && data.status.toLowerCase() === 'completed') {
                if (data.final_payment_status === 'paid') {
                    appointmentPaymentStatusEl.textContent = 'Paid';
                    appointmentPaymentStatusEl.className = 'font-medium text-green-600';
                } else if (data.final_payment_status === 'paid_cash') {
                    appointmentPaymentStatusEl.textContent = 'Paid (Cash)';
                    appointmentPaymentStatusEl.className = 'font-medium text-green-600';
                } else if (parseFloat(data.commitment_fee || 0) > 0) {
                    appointmentPaymentStatusEl.textContent = 'Partial (Commitment Fee Only)';
                    appointmentPaymentStatusEl.className = 'font-medium text-yellow-600';
                } else {
                    appointmentPaymentStatusEl.textContent = 'Unpaid';
                    appointmentPaymentStatusEl.className = 'font-medium text-red-600';
                }
            } else {
                appointmentPaymentStatusEl.textContent = 'N/A';
                appointmentPaymentStatusEl.className = 'font-medium text-gray-600';
            }
        }
        
        // Handle payment amount
        if (appointmentPaymentAmountEl) {
            if ((data.final_payment_status === 'paid' || data.final_payment_status === 'paid_cash') && data.total_amount) {
                appointmentPaymentAmountEl.textContent = `₱${parseFloat(data.total_amount).toFixed(2)}`;
            } else if (parseFloat(data.commitment_fee || 0) > 0) {
                appointmentPaymentAmountEl.textContent = `₱${parseFloat(data.commitment_fee).toFixed(2)} (Commitment Fee)`;
            } else {
                appointmentPaymentAmountEl.textContent = '₱0.00';
            }
        }

        // Set button data - Add null checks here
        if (acceptBtn && eventId) acceptBtn.dataset.id = eventId;
        if (declineBtn && eventId) declineBtn.dataset.id = eventId;
        if (completeBtn && eventId) completeBtn.dataset.id = eventId;

        resetModalState();

        const status = data.status ? data.status.toLowerCase() : 'pending';

        // Show/hide buttons based on status
        if (status === 'pending') {
            if (acceptBtn) acceptBtn.style.display = 'inline-block';
            if (declineBtn) declineBtn.style.display = 'inline-block';
            if (completeBtn) completeBtn.style.display = 'none';
        } else if (status === 'accepted') {
            if (acceptBtn) acceptBtn.style.display = 'none';
            if (declineBtn) declineBtn.style.display = 'none';
            if (completeBtn) completeBtn.style.display = 'inline-block';
        } else {
            // declined / completed
            if (acceptBtn) acceptBtn.style.display = 'none';
            if (declineBtn) declineBtn.style.display = 'none';
            if (completeBtn) completeBtn.style.display = 'none';
        }

        

        // Handle no-show form
        const noShowForm = document.getElementById('noShowForm');
        if (noShowForm && data.id) {
            noShowForm.action = `/appointments/${data.id}/no-show`;

            function updateNoShowButton() {
                try {
                    const appointmentStatus = data.status ? data.status.toLowerCase() : '';
                    const appointmentTime24 = to24HourTime(data.time);
                    const appointmentDateTime = new Date(data.date + 'T' + appointmentTime24);
                    const now = new Date();

                    if (appointmentStatus === 'accepted' && now > appointmentDateTime) {
                        noShowForm.style.display = 'inline-block';
                    } else {
                        noShowForm.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Error in updateNoShowButton:', error);
                    noShowForm.style.display = 'none';
                }
            }

            updateNoShowButton();
            if (window.noShowInterval) clearInterval(window.noShowInterval);
            window.noShowInterval = setInterval(updateNoShowButton, 60000);
        }

        appointmentModal.style.display = 'flex';
    }

    function resetModalState() {
        if (declineReasonContainer) declineReasonContainer.style.display = 'none';
        const declineReasonEl = document.getElementById('declineReason');
        if (declineReasonEl) declineReasonEl.value = '';
        if (acceptBtn) acceptBtn.style.display = 'inline-block';
        if (declineBtn) declineBtn.style.display = 'inline-block';
    }

    // Event listeners
    if (closeAppointmentModal) {
        closeAppointmentModal.addEventListener('click', () => {
            if (appointmentModal) appointmentModal.style.display = 'none';
            resetModalState();
        });
    }

    // Accept Appointment
    if (acceptBtn) {
        acceptBtn.addEventListener('click', function () {
            showSpinnerOnButton(this);
            const appointmentId = this.dataset.id;
            if (!appointmentId) {
                restoreButton(this, 'Accept');
                return alert('No appointment selected.');
            }

            fetch(`/appointments/accept/${appointmentId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to accept appointment.');
                return response.json();
            })
            .then(data => {
                restoreButton(this, 'Accept');
               if (data.success) {
                    if (appointmentModal) appointmentModal.style.display = 'none';
                    window.location.reload();
                } else {
                    alert(data.message || 'Error accepting appointment.');
                }
            })
            .catch(error => {
                console.error(error);
                restoreButton(this, 'Accept');
                alert('Error accepting appointment.');
            });
        });
    }

    // Decline Button: Shows reason input
    if (declineBtn) {
        declineBtn.addEventListener('click', function () {
            if (acceptBtn) acceptBtn.style.display = 'none';
            if (declineBtn) declineBtn.style.display = 'none';
            if (declineReasonContainer) declineReasonContainer.style.display = 'block';
        });
    }

    // Confirm Decline Button
    if (confirmDeclineBtn) {
        confirmDeclineBtn.addEventListener('click', function () {
            const appointmentId = declineBtn ? declineBtn.dataset.id : null;
            const declineReasonEl = document.getElementById('declineReason');
            const declineReason = declineReasonEl ? declineReasonEl.value.trim() : '';

            if (!appointmentId) {
                alert('No appointment selected.');
                return;
            }

            if (!confirm('Are you sure you want to decline this appointment?')) {
                return;
            }

            showSpinnerOnButton(this);
            
            fetch(`/appointments/decline/${appointmentId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ reason: declineReason })
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to decline appointment.');
                return response.json();
            })
            .then(data => {
                restoreButton(this, 'Confirm Decline');
                if (appointmentModal) appointmentModal.style.display = 'none';
                resetModalState();
                window.location.reload();
            })
            .catch(error => {
                console.error(error);
                restoreButton(this, 'Confirm Decline');
                alert('Error declining appointment.');
            });
        });
    }

    // Cancel Decline Button
    if (cancelDeclineBtn) {
        cancelDeclineBtn.addEventListener('click', function () {
            if (declineReasonContainer) declineReasonContainer.style.display = 'none';
            const declineReasonEl = document.getElementById('declineReason');
            if (declineReasonEl) declineReasonEl.value = '';
            if (acceptBtn) acceptBtn.style.display = 'inline-block';
            if (declineBtn) declineBtn.style.display = 'inline-block';
        });
    }

    // Complete Appointment
    if (completeBtn) {
        completeBtn.addEventListener('click', function () {
            const appointmentId = this.dataset.id;
            if (!appointmentId) {
                alert('No appointment selected.');
                return;
            }

            if (!confirm('Are you sure you want to mark this appointment as completed?')) return;
            
            showSpinnerOnButton(this);
            
            fetch(`/appointments/${appointmentId}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { 
                        throw new Error(err.message || 'Failed to mark as completed'); 
                    });
                }
                return response.json();
            })
            .then(data => {
                restoreButton(this, 'Complete');
                if (appointmentModal) appointmentModal.style.display = 'none';
                window.location.reload();
            })
            .catch(error => {
                console.error('Error details:', error);
                restoreButton(this, 'Complete');
                alert('Error marking appointment as completed: ' + error.message);
            });
        });
    }

    // Close modal when clicking outside
    if (appointmentModal) {
        appointmentModal.addEventListener('click', function(e) {
            if (e.target === appointmentModal) {
                appointmentModal.style.display = 'none';
                resetModalState();
            }
        });
    }

    // Clear intervals when page unloads
    window.addEventListener('beforeunload', function() {
        if (window.noShowInterval) {
            clearInterval(window.noShowInterval);
        }
    });
});

</script>
