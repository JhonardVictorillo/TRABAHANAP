 

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
    
   <div id="appointmentModal" class="Apptmodal hidden">
  <div class="Apptmodal-content enhanced-modal" style="max-width:430px; padding:2.5rem 2rem 2rem 2rem;">
    <button id="closeAppointmentModal" class="close-button enhanced-close" title="Close">
      <i class="fas fa-times"></i>
    </button>
    <div class="enhanced-title" style="margin-bottom:1.2rem;">
      <i class="fas fa-calendar-check" style="color:#2563eb; margin-right:8px;"></i>
      Appointment Details
    </div>
    <div class="enhanced-body" style="margin-bottom:1.2rem;">
      <div class="appt-details-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:12px 18px;">
        <div class="appt-detail">
        <span class="appt-label"><i class="fas fa-briefcase" style="color:#2563eb;"></i> Service(s):</span>
        <span id="appointmentSubservices" class="appt-value"></span>
      </div>
      <div class="appt-detail">
          <span class="appt-label"><i class="fas fa-user" style="color:#2563eb;"></i> Name:</span>
          <span id="appointmentName" class="appt-value"></span>
        </div>
        <div class="appt-detail">
          <span class="appt-label"><i class="fas fa-calendar-day" style="color:#2563eb;"></i> Date:</span>
          <span id="appointmentDate" class="appt-value"></span>
        </div>
        <div class="appt-detail">
          <span class="appt-label"><i class="fas fa-clock" style="color:#2563eb;"></i> Time:</span>
          <span id="appointmentTime" class="appt-value"></span>
        </div>
        <div class="appt-detail">
          <span class="appt-label"><i class="fas fa-map-marker-alt" style="color:#2563eb;"></i> Address:</span>
          <span id="appointmentAddress" class="appt-value"></span>
        </div>
        <div class="appt-detail">
          <span class="appt-label"><i class="fas fa-phone" style="color:#2563eb;"></i> Contact:</span>
          <span id="appointmentContact" class="appt-value"></span>
        </div>
        <div class="appt-detail">
          <span class="appt-label"><i class="fas fa-info-circle" style="color:#2563eb;"></i> Status:</span>
          <span id="appointmentStatus" class="appt-value"></span>
        </div>
        <div class="appt-detail">
          <span class="appt-label"><i class="fas fa-money-bill-wave" style="color:#2563eb;"></i> Payment Status:</span>
          <span id="appointmentPaymentStatus" class="appt-value"></span>
        </div>
        <div class="appt-detail">
          <span class="appt-label"><i class="fas fa-coins" style="color:#2563eb;"></i> Payment Amount:</span>
          <span id="appointmentPaymentAmount" class="appt-value"></span>
        </div>
      </div>
      <div class="appt-notes" style="margin-top:1.2rem;">
        <span class="appt-label"><i class="fas fa-sticky-note" style="color:#2563eb;"></i> Notes:</span>
        <span id="appointmentNotes" class="appt-value"></span>
      </div>
    </div>
    <div class="enhanced-actions" style="margin-top:1.5rem;gap:0.7rem;justify-content:center;">
      <button id="acceptAppointmentBtn" class="action-button accept-button enhanced-btn" data-id="">
        <i class="fas fa-check-circle"></i> 
        <span class="btn-text">Accept</span>
        <span class="btn-spinner" style="display:none;">
          <i class="fas fa-spinner fa-spin"></i>
        </span>
      </button>
      <button id="declineAppointmentBtn" class="action-button decline-button enhanced-btn" data-id="">
        <i class="fas fa-times-circle"></i> 
        <span class="btn-text">Decline</span>
        <span class="btn-spinner" style="display:none;">
          <i class="fas fa-spinner fa-spin"></i>
        </span>
      </button>
      <button id="completeAppointmentBtn" class="action-button complete-button enhanced-btn" data-id="">
        <i class="fas fa-flag-checkered"></i> 
        <span class="btn-text">Complete</span>
        <span class="btn-spinner" style="display:none;">
          <i class="fas fa-spinner fa-spin"></i>
        </span>
      </button>
    </div>
    <div id="declineReasonContainer" style="display:none;margin-top:1.2rem;">
      <label for="declineReason" class="enhanced-label" style="margin-bottom:0.4rem;">
        <i class="fas fa-comment-dots"></i> Reason for Declining:
      </label>
      <textarea id="declineReason" placeholder="Enter reason..." rows="3" class="enhanced-textarea"></textarea>
      <div style="display:flex;gap:0.7rem;justify-content:center;">
        <button id="confirmDeclineBtn" class="action-button decline-button enhanced-btn">
          <i class="fas fa-check"></i> 
         <span class="btn-text">Confirm Decline</span>
        <span class="btn-spinner" style="display:none;">
          <i class="fas fa-spinner fa-spin"></i>
        </span>
        </button>
        <button id="cancelDeclineBtn" class="action-button cancel-button enhanced-btn">
          <i class="fas fa-undo"></i> Cancel
        </button>
      </div>
      <form id="noShowForm" method="POST" action="{{ route('appointments.no_show', 0) }}" style="display:none;margin-top:1rem;">
        @csrf
        <button type="submit" class="action-button warning-button enhanced-btn">
          <i class="fas fa-user-slash"></i> 
          <span class="btn-text">Mark Customer as No-Show</span>
          <span class="btn-spinner" style="display:none;">
            <i class="fas fa-spinner fa-spin"></i>
          </span>
        </button>
      </form>
    </div>
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

  function openAppointmentModal(data, eventId) {
    console.log('Appointment data:', data);

    document.getElementById('appointmentName').textContent = data.name || 'N/A';
    document.getElementById('appointmentDate').textContent = data.date || 'N/A';
    document.getElementById('appointmentTime').textContent = convertTo12HourFormat(data.time) || 'N/A';
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
