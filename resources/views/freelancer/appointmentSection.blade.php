 

   <!-- Appointment Calendar -->
   <section class="appointment-calendar" id="appointmentCalendar" style="display: none;">
      <div class="text-center text-3xl font-bold mb-5">Appointment Calendar</div>
      
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
    
 <div id="appointmentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <div class="flex items-center gap-2">
               <i class="ri-calendar-check-line text-2xl text-blue-600"></i>
                <h5 class="text-lg font-semibold text-gray-800">Appointment Details</h5>
            </div>
            <button id="closeAppointmentModal" type="button" class="text-gray-400 hover:text-gray-600 close-modal">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        <div class="px-6 py-4 space-y-3">
            <div class="flex items-center gap-2">
                <i class="ri-user-line text-primary"></i>
                <span class="font-medium">Customer Name:</span>
                <span id="appointmentName"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="ri-briefcase-line text-primary"></i>
                <span class="font-medium">Service(s):</span>
                <span id="appointmentSubservices"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="ri-calendar-line text-primary"></i>
                <span class="font-medium">Date:</span>
                <span id="appointmentDate"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="ri-time-line text-primary"></i>
                <span class="font-medium">Time:</span>
                <div id="appointmentTime" class="flex-1">N/A</div>
            </div>
            <div class="flex items-center gap-2">
                <i class="ri-map-pin-line text-primary"></i>
                <span class="font-medium">Address:</span>
                <span id="appointmentAddress"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="ri-phone-line text-primary"></i>
                <span class="font-medium">Contact:</span>
                <span id="appointmentContact"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="ri-information-line text-primary"></i>
                <span class="font-medium">Status:</span>
                <span id="appointmentStatus"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="ri-money-dollar-circle-line text-primary"></i>
                <span class="font-medium">Payment Status:</span>
                <span id="appointmentPaymentStatus"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="ri-money-dollar-circle-line text-primary"></i>
                <span class="font-medium">Payment Amount:</span>
                <span id="appointmentPaymentAmount"></span>
            </div>
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2">
                    <i class="ri-sticky-note-line text-primary"></i>
                    <span class="font-medium">Notes:</span>
                </div>
                <div class="ml-6 mt-1 p-2 bg-gray-50 rounded">
                    <span id="appointmentNotes" class="text-sm text-gray-700"></span>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button id="acceptAppointmentBtn" class="btn flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded" data-id="">
                <i class="ri-check-line"></i> 
                <span class="btn-text">Accept</span>
                <span class="btn-spinner" style="display:none;">
                    <i class="ri-loader-4-line animate-spin"></i>
                </span>
            </button>
            <button id="declineAppointmentBtn" class="btn flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded" data-id="">
                <i class="ri-close-line"></i> 
                <span class="btn-text">Decline</span>
                <span class="btn-spinner" style="display:none;">
                    <i class="ri-loader-4-line animate-spin"></i>
                </span>
            </button>
            <button id="completeAppointmentBtn" class="btn flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded" data-id="">
                <i class="ri-check-double-line"></i> 
                <span class="btn-text">Complete</span>
                <span class="btn-spinner" style="display:none;">
                    <i class="ri-loader-4-line animate-spin"></i>
                </span>
            </button>
            
            
        </div>

        <div id="declineReasonContainer" style="display:none;" class="px-6 py-4 border-t">
            <label for="declineReason" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="ri-error-warning-line text-red-500"></i> Reason for Declining:
            </label>
            <textarea id="declineReason" placeholder="Enter reason for declining the appointment..." rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"></textarea>
            <div class="flex justify-end gap-2 mt-4">
                <button id="confirmDeclineBtn" class="btn flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                    <i class="ri-check-line"></i> 
                    <span class="btn-text">Confirm Decline</span>
                    <span class="btn-spinner" style="display:none;">
                        <i class="ri-loader-4-line animate-spin"></i>
                    </span>
                </button>
                <button id="cancelDeclineBtn" class="btn flex items-center gap-1 bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded">
                    <i class="ri-arrow-go-back-line"></i> Cancel
                </button>
            </div>
        </div>

        <form id="noShowForm" method="POST" action="{{ route('appointments.no_show', 0) }}" style="display:none;" class="px-6 py-4 border-t">
            @csrf
            <button type="submit" class="btn flex items-center gap-1 w-full bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded justify-center">
                <i class="ri-user-unfollow-line"></i> 
                <span class="btn-text">Mark Customer as No-Show</span>
                <span class="btn-spinner" style="display:none;">
                    <i class="ri-loader-4-line animate-spin"></i>
                </span>
            </button>
        </form>
    </div>
</div>
    
<script>
  const appointmentModal = document.getElementById('appointmentModal');
  const closeAppointmentModal = document.getElementById('closeAppointmentModal');

  const acceptBtn = document.getElementById('acceptAppointmentBtn');
  const declineBtn = document.getElementById('declineAppointmentBtn');
  const completeBtn = document.getElementById('completeAppointmentBtn');

  const declineReasonContainer = document.getElementById('declineReasonContainer');
  const confirmDeclineBtn = document.getElementById('confirmDeclineBtn');
  const cancelDeclineBtn = document.getElementById('cancelDeclineBtn'); // Add this button in your HTML if not yet!

  closeAppointmentModal.addEventListener('click', () => {
    appointmentModal.style.display = 'none';
    resetModalState();
  });


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

function formatTimeRange(startTime, durationMinutes) {
    if (!startTime) return 'N/A';
    
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
        
        return `${startFormatted} - ${endFormatted}`;
    } catch (error) {
        console.error("Error in formatTimeRange:", error);
        return `${startTime} - Error`;
    }
}

  function openAppointmentModal(data, eventId) {
    console.log('Appointment data:', data);

    document.getElementById('appointmentName').textContent = data.name || 'N/A';
    document.getElementById('appointmentDate').textContent = data.date || 'N/A';
   // Get the duration from the appointment data
    const duration = data.duration || 60; // Default to 60 minutes if not provided
    
    // Format time as a range (start - end) with service duration
    let timeDisplay = formatTimeRange(data.time, duration);
    
    // Add duration information
    timeDisplay += `<span class="text-xs text-gray-500 block mt-1">
        ${duration} minute service
    </span>`;
    
    document.getElementById('appointmentTime').innerHTML = timeDisplay;
    document.getElementById('appointmentAddress').textContent = data.address || 'N/A';
    document.getElementById('appointmentContact').textContent = data.contact || 'N/A';
    document.getElementById('appointmentStatus').textContent = data.status || 'N/A';
    document.getElementById('appointmentNotes').textContent = data.notes || 'None';
    document.getElementById('appointmentSubservices').textContent =
     Array.isArray(data.subservices) && data.subservices.length
    ? data.subservices.join(', ')
    : 'N/A';
    const paymentStatusElement = document.getElementById('appointmentPaymentStatus');
    if (data.status.toLowerCase() === 'completed') {
        if (data.final_payment_status === 'paid') {
            paymentStatusElement.textContent = 'Paid';
            paymentStatusElement.className = 'font-medium text-green-600';
         } else if (data.final_payment_status === 'paid_cash') {
            paymentStatusElement.textContent = 'Paid (Cash)';
            paymentStatusElement.className = 'font-medium text-green-600';
        } else if (parseFloat(data.commitment_fee || 0) > 0) {
            paymentStatusElement.textContent = 'Partial (Commitment Fee Only)';
            paymentStatusElement.className = 'font-medium text-yellow-600';
        } else {
            paymentStatusElement.textContent = 'Unpaid';
            paymentStatusElement.className = 'font-medium text-red-600';
        }
    } else {
        paymentStatusElement.textContent = 'N/A';
        paymentStatusElement.className = 'font-medium text-gray-600';
    }
    
    // Payment amount
    const paymentAmountElement = document.getElementById('appointmentPaymentAmount');
   if ((data.final_payment_status === 'paid' || data.final_payment_status === 'paid_cash') && data.total_amount) {
    paymentAmountElement.textContent = `₱${parseFloat(data.total_amount).toFixed(2)}`;
    } else if (parseFloat(data.commitment_fee || 0) > 0) {
        paymentAmountElement.textContent = `₱${parseFloat(data.commitment_fee).toFixed(2)} (Commitment Fee)`;
    } else {
        paymentAmountElement.textContent = '₱0.00';
    }


    acceptBtn.dataset.id = eventId;
    declineBtn.dataset.id = eventId;
    completeBtn.dataset.id = eventId;

    resetModalState();

    const status = data.status.toLowerCase();

    if (status === 'pending') {
      acceptBtn.style.display = 'inline-block';
      declineBtn.style.display = 'inline-block';
      completeBtn.style.display = 'none';
    } else if (status === 'accepted') {
      acceptBtn.style.display = 'none';
      declineBtn.style.display = 'none';
      completeBtn.style.display = 'inline-block';
    } else {
      // declined / completed
      acceptBtn.style.display = 'none';
      declineBtn.style.display = 'none';
      completeBtn.style.display = 'none';
    }


     const noShowForm = document.getElementById('noShowForm');
if (noShowForm) {
    noShowForm.action = `/appointments/${data.id}/no-show`;

    function to24HourTime(time12h) {
        const [time, modifier] = time12h.split(' ');
        let [hours, minutes] = time.split(':');
        if (hours === '12') hours = '00';
        if (modifier === 'PM') hours = parseInt(hours, 10) + 12;
        return `${hours.toString().padStart(2, '0')}:${minutes}:00`;
    }

    function updateNoShowButton() {
        const appointmentStatus = data.status ? data.status.toLowerCase() : '';
        const appointmentTime24 = to24HourTime(data.time);
        const appointmentDateTime = new Date(data.date + 'T' + appointmentTime24);
        const now = new Date();

        if (appointmentStatus === 'accepted' && now > appointmentDateTime) {
            noShowForm.style.display = 'inline-block';
        } else {
            noShowForm.style.display = 'none';
        }
    }

    updateNoShowButton();
    window.noShowInterval && clearInterval(window.noShowInterval);
    window.noShowInterval = setInterval(updateNoShowButton, 60000);
}

    appointmentModal.style.display = 'flex';
  }

//12-hour format conversion function
  function convertTo12HourFormat(time) {
    if (!time) return 'N/A'; // Handle empty or invalid time

    const [hour, minute] = time.split(':').map(Number);
    const period = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour % 12 || 12; // Convert 0 to 12 for midnight
    return `${hour12}:${minute.toString().padStart(2, '0')} ${period}`;
}

  // Reset modal buttons and inputs when opened/closed
  function resetModalState() {
    declineReasonContainer.style.display = 'none';
    document.getElementById('declineReason').value = '';
    acceptBtn.style.display = 'inline-block';
    declineBtn.style.display = 'inline-block';
  }

  // Accept Appointment
  acceptBtn.addEventListener('click', function () {
     showSpinnerOnButton(this);
    const appointmentId = this.dataset.id;
    if (!appointmentId) return alert('No appointment selected.');

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
      
      appointmentModal.style.display = 'none';
      window.location.reload(); // Reload appointments list/table
    })
    .catch(error => {
      console.error(error);
      alert('Error accepting appointment.');
    });
  });

  // Decline Button: Shows reason input
  declineBtn.addEventListener('click', function () {
    // Hide accept/decline buttons
    acceptBtn.style.display = 'none';
    declineBtn.style.display = 'none';

    // Show the textarea and confirm/cancel buttons
    declineReasonContainer.style.display = 'block';
  });

  // Confirm Decline Button
  confirmDeclineBtn.addEventListener('click', function () {
   
    const appointmentId = declineBtn.dataset.id;
    const declineReason = document.getElementById('declineReason').value.trim();
     
    

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
      
      appointmentModal.style.display = 'none';
      resetModalState();
      window.location.reload(); // Refresh your data
    })
    .catch(error => {
      console.error(error);
      alert('Error declining appointment.');
    });
  });

  // Cancel Decline Button
  if (cancelDeclineBtn) {
    cancelDeclineBtn.addEventListener('click', function () {
      declineReasonContainer.style.display = 'none';
      document.getElementById('declineReason').value = '';
      acceptBtn.style.display = 'inline-block';
      declineBtn.style.display = 'inline-block';
    });
  }

  // Complete Appointment
completeBtn.addEventListener('click', function () {
    const appointmentId = this.dataset.id;
    if (!appointmentId) return alert('No appointment selected.');

    if (!confirm('Are you sure you want to mark this appointment as completed?')) return;
    showSpinnerOnButton(this);
    fetch(`/appointments/${appointmentId}/complete`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json' // Add this line to request JSON response
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw new Error(err.message || 'Failed to mark as completed'); });
        }
        return response.json();
    })
    .then(data => {
       restoreButton(this, 'Complete');
      
        appointmentModal.style.display = 'none';
       window.location.reload(); // Refresh your events if using fullCalendar
    })
    .catch(error => {
        console.error('Error details:', error);
        alert('Error marking appointment as completed: ' + error.message);
    });
});


</script>
