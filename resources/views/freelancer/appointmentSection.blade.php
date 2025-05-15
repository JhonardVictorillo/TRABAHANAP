 

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
  <div class="Apptmodal-content enhanced-modal">
    <button id="closeAppointmentModal" class="close-button enhanced-close" title="Close">
      <i class="fas fa-times"></i>
    </button>
    <h2 class="Apptmodal-title enhanced-title">
      <i class="fas fa-calendar-check" style="color:#118f39;"></i> Appointment Details
    </h2>
    <div class="Apptmodal-body enhanced-body">
      <p><i class="fas fa-user"></i> <strong>Name:</strong> <span id="appointmentName" class="font-medium"></span></p>
      <p><i class="fas fa-calendar-day"></i> <strong>Date:</strong> <span id="appointmentDate" class="font-medium"></span></p>
      <p><i class="fas fa-clock"></i> <strong>Time:</strong> <span id="appointmentTime" class="font-medium"></span></p>
      <p><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> <span id="appointmentAddress" class="font-medium"></span></p>
      <p><i class="fas fa-phone"></i> <strong>Contact:</strong> <span id="appointmentContact" class="font-medium"></span></p>
      <p><i class="fas fa-info-circle"></i> <strong>Status:</strong> <span id="appointmentStatus" class="font-medium"></span></p>
      <p><i class="fas fa-sticky-note"></i> <strong>Notes:</strong> <span id="appointmentNotes" class="font-medium"></span></p>
    </div>
    <div class="modal-actions enhanced-actions">
      <button id="acceptAppointmentBtn" class="action-button accept-button enhanced-btn" data-id="">
        <i class="fas fa-check-circle"></i> Accept
      </button>
      <button id="declineAppointmentBtn" class="action-button decline-button enhanced-btn" data-id="">
        <i class="fas fa-times-circle"></i> Decline
      </button>
      <button id="completeAppointmentBtn" class="action-button complete-button enhanced-btn" data-id="">
        <i class="fas fa-flag-checkered"></i> Complete
      </button>
      <div id="declineReasonContainer" style="display: none; margin-top: 1rem;">
        <label for="declineReason" class="enhanced-label">
          <i class="fas fa-comment-dots"></i> Reason for Declining:
        </label>
        <textarea id="declineReason" placeholder="Enter reason..." rows="3" class="enhanced-textarea"></textarea>
        <button id="confirmDeclineBtn" class="action-button decline-button enhanced-btn">
          <i class="fas fa-check"></i> Confirm Decline
        </button>
        <button id="cancelDeclineBtn" class="action-button cancel-button enhanced-btn">
          <i class="fas fa-undo"></i> Cancel
        </button>
        <form id="noShowForm" method="POST" action="{{ route('appointments.no_show', 0) }}" style="display:none; margin-left: 10px;">
          @csrf
          <button type="submit" class="action-button warning-button enhanced-btn">
              <i class="fas fa-user-slash"></i> Mark Customer as No-Show
          </button>
      </form>
      </div>
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
      alert('Appointment accepted!');
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
      alert(data.message || 'Appointment declined!');
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

    fetch(`/appointments/${appointmentId}/complete`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json'
      }
    })
    .then(response => {
      if (!response.ok) throw new Error('Failed to mark appointment as completed.');
      return response.json();
    })
    .then(data => {
      alert('Appointment marked as completed!');
      appointmentModal.style.display = 'none';
      calendar.refetchEvents(); // Refresh your events if using fullCalendar
    })
    .catch(error => {
      console.error(error);
      alert('Error marking appointment as completed.');
    });
  });


  const noShowForm = document.getElementById('noShowForm');
if (noShowForm) {
    noShowForm.action = `/appointments/${eventId}/no-show`;

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
</script>
