<!-- Bookings Section -->
<div id="bookingsSection" class="details-section" style="display: none;">
    <h2 class="section-title"><span class="material-symbols-outlined align-middle">event_note</span> All Bookings</h2>
    <div class="booking-table-container">

    <!-- Search Bar -->
    <form method="GET" action="{{ route('admin.dashboard') }}" class="table-search-form" style="margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.5rem;">
    <input type="hidden" name="section" value="bookings">
    <div style="position: relative; width: 240px;">
        <span class="material-symbols-outlined"
              style="color:#2563eb; position: absolute; left: 12px; top: 50%; transform: translateY(-50%); pointer-events: none;">
            search
        </span>
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by customer name..."
            style="padding: 8px 14px 8px 38px; border: 1px solid #ccc; border-radius: 20px; font-size: 15px; width: 100%; background: #f2f2f2;"
        >
    </div>
            <button type="submit" style="padding: 8px 18px; border-radius: 20px; background: #2563eb; color: #fff; border: none; font-size: 15px; cursor: pointer;">
                Search
            </button>
           
        </form>
        <!-- Bookings Table -->
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Freelancer</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Fee Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($appointments as $appt)
                <tr>
                    <td>{{ $appt->id }}</td>
                    <td>{{ $appt->customer->firstname ?? '' }} {{ $appt->customer->lastname ?? '' }}</td>
                    <td>{{ $appt->freelancer->firstname ?? '' }} {{ $appt->freelancer->lastname ?? '' }}</td>
                    <td>{{ $appt->date }}</td>
                    <td>{{ $appt->time }}</td>
                    <td>
                        <span class="status-badge" style="background:{{ getStatusColor($appt->status) }}">
                            {{ ucfirst(str_replace('_', ' ', $appt->status)) }}
                        </span>
                    </td>
                    <td>{{ ucfirst($appt->fee_status) }}</td>
                    <td>
                    @php
                        $apptData = [
                            'id' => $appt->id,
                            'customer' => [
                                'firstname' => $appt->customer->firstname ?? '',
                                'lastname' => $appt->customer->lastname ?? '',
                            ],
                            'freelancer' => [
                                'firstname' => $appt->freelancer->firstname ?? '',
                                'lastname' => $appt->freelancer->lastname ?? '',
                            ],
                            'date' => $appt->date,
                            'time' => $appt->time,
                            'status' => $appt->status,
                            'fee_status' => $appt->fee_status,
                            'notes' => $appt->notes,
                        ];
                    @endphp
                    <a href="javascript:void(0);" 
                    class="btn btn-sm btn-info view-booking-btn"
                    data-appt='@json($apptData)'>
                    View
                    </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- Pagination Links -->
       <div class="category-pagination-container">
            @if($appointments->previousPageUrl())
            <a href="{{ $appointments->appends(['search' => request('search'), 'activeSection' => 'violations', 'role' => request('role', 'all')])->previousPageUrl() }}" class="category-pagination-btn">
                <i class="fas fa-arrow-left"></i> Previous
            </a>
            @else
            <button class="category-pagination-btn category-btn-disabled" disabled>
                <i class="fas fa-arrow-left"></i> Previous
            </button>
            @endif
            
            @if($appointments->hasMorePages())
            <a href="{{ $appointments->appends(['search' => request('search'), 'activeSection' => 'violations', 'role' => request('role', 'all')])->nextPageUrl() }}" class="category-pagination-btn">
                Next <i class="fas fa-arrow-right"></i>
            </a>
            @else
            <button class="category-pagination-btn category-btn-disabled" disabled>
                Next <i class="fas fa-arrow-right"></i>
            </button>
            @endif
        </div>
    </div>
</div>



<!-- Booking Details Modal -->
<div id="bookingDetailsModal" class="booking-details-modal">
    <div class="booking-details-modal-content">
        <div class="booking-details-modal-header">
            <h3>
                <span class="material-symbols-outlined">event_available</span>
                Appointment Details
            </h3>
            <span class="booking-details-modal-close">&times;</span>
        </div>
        
        <div class="booking-details-body" id="bookingDetailsBody">
            <!-- Header with status and ID -->
            <div class="booking-details-header">
                <div class="booking-details-status">
                    <span id="modalStatus" class="booking-status"></span>
                </div>
                <div class="booking-details-id">
                    <h5>Appointment ID:</h5>
                    <h2 id="modalApptId">-</h2>
                </div>
            </div>
            
            <!-- Appointment schedule section -->
            <div class="booking-details-section">
                <h4>
                    <span class="material-symbols-outlined">schedule</span>
                    Appointment Schedule
                </h4>
                <div class="booking-details-stats">
                    <div class="booking-details-stat">
                        <span class="booking-details-stat-label">Date</span>
                        <span class="booking-details-stat-value" id="modalDate">-</span>
                    </div>
                    <div class="booking-details-stat">
                        <span class="booking-details-stat-label">Time</span>
                        <span class="booking-details-stat-value" id="modalTime">-</span>
                    </div>
                    <div class="booking-details-stat">
                        <span class="booking-details-stat-label">Fee Status</span>
                        <span class="booking-details-stat-value" id="modalFeeStatus">-</span>
                    </div>
                </div>
            </div>
            
            <!-- Customer info -->
            <div class="booking-details-section">
                <h4>
                    <span class="material-symbols-outlined">person</span>
                    Customer Information
                </h4>
                <div class="booking-details-profile">
                    <div class="booking-user-avatar" id="customerAvatar">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <div class="booking-details-user-info">
                        <h5 id="modalCustomer">-</h5>
                    </div>
                </div>
            </div>
            
            <!-- Freelancer info -->
            <div class="booking-details-section">
                <h4>
                    <span class="material-symbols-outlined">engineering</span>
                    Freelancer Information
                </h4>
                <div class="booking-details-profile">
                    <div class="booking-user-avatar" id="freelancerAvatar">
                        <span class="material-symbols-outlined">engineering</span>
                    </div>
                    <div class="booking-details-user-info">
                        <h5 id="modalFreelancer">-</h5>
                    </div>
                </div>
            </div>
            
            <!-- Notes -->
            <div class="booking-details-section" id="booking-notes-section">
                <h4>
                    <span class="material-symbols-outlined">note</span>
                    Notes
                </h4>
                <div class="booking-details-notes" id="modalNotes">
                    No notes available.
                </div>
            </div>
            
            <!-- Action buttons -->
            <div class="booking-details-actions">
                <button class="booking-details-btn booking-details-btn-back" id="closeBookingModal">
                    <span class="material-symbols-outlined">close</span> Close
                </button>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // View booking button click handler
    document.querySelectorAll('.view-booking-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Get appointment data from the data-appt attribute
            const appt = JSON.parse(this.getAttribute('data-appt'));
            showBookingDetails(appt);
        });
    });

    // Close modal handlers
    document.querySelector('.booking-details-modal-close').addEventListener('click', function() {
        document.getElementById('bookingDetailsModal').style.display = 'none';
    });

    document.getElementById('closeBookingModal').addEventListener('click', function() {
        document.getElementById('bookingDetailsModal').style.display = 'none';
    });

    // Close when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target == document.getElementById('bookingDetailsModal')) {
            document.getElementById('bookingDetailsModal').style.display = 'none';
        }
    });
});

// Show booking details function
function showBookingDetails(appt) {
    // Show modal
    const modal = document.getElementById('bookingDetailsModal');
    modal.style.display = 'block';
    
    // Set ID
    document.getElementById('modalApptId').textContent = appt.id;
    
    // Set status badge
    const statusText = appt.status.replace(/_/g, ' ');
    const statusElement = document.getElementById('modalStatus');
    statusElement.textContent = statusText.charAt(0).toUpperCase() + statusText.slice(1);
    statusElement.className = `booking-status booking-status-${appt.status.toLowerCase()}`;
    
    // Set appointment schedule
    document.getElementById('modalDate').textContent = appt.date || 'Not set';
    document.getElementById('modalTime').textContent = appt.time || 'Not set';
    document.getElementById('modalFeeStatus').textContent = appt.fee_status ? 
        (appt.fee_status.charAt(0).toUpperCase() + appt.fee_status.slice(1)) : 'Unknown';
    
    // Set customer info
    if (appt.customer) {
        document.getElementById('modalCustomer').textContent = 
            `${appt.customer.firstname || ''} ${appt.customer.lastname || ''}`.trim() || 'Unknown';
    } else {
        document.getElementById('modalCustomer').textContent = 'Customer information not available';
    }
    
    // Set freelancer info
    if (appt.freelancer) {
        document.getElementById('modalFreelancer').textContent = 
            `${appt.freelancer.firstname || ''} ${appt.freelancer.lastname || ''}`.trim() || 'Unknown';
    } else {
        document.getElementById('modalFreelancer').textContent = 'Freelancer information not available';
    }
    
    // Set notes
    if (appt.notes && appt.notes.trim() !== '') {
        document.getElementById('modalNotes').textContent = appt.notes;
    } else {
        document.getElementById('modalNotes').textContent = 'No notes available for this appointment.';
    }
}

</script>