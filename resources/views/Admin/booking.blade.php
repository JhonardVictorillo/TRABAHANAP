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
            <a href="{{ $appointments->appends(['search' => request('search'), 'section' => 'bookings'])->previousPageUrl() }}" class="category-pagination-btn">
                <i class="fas fa-arrow-left"></i> Previous
            </a>
            @else
            <button class="category-pagination-btn category-btn-disabled" disabled>
                <i class="fas fa-arrow-left"></i> Previous
            </button>
            @endif
            
            @if($appointments->hasMorePages())
            <a href="{{ $appointments->appends(['search' => request('search'), 'section' => 'bookings'])->nextPageUrl() }}" class="category-pagination-btn">
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
<div id="bookingDetailsModal" class="modal">
    <div class="modal-content" style="max-width:400px; min-width:320px; text-align:left; position:relative;">
        <button type="button" class="close close-modal" aria-label="Close" style="right:18px;top:18px;">&times;</button>
        <h2 style="display:flex;align-items:center;gap:0.5rem;font-size:1.5rem;font-weight:700;color:#2563eb;margin-bottom:1.5rem;">
            <span class="material-symbols-outlined align-middle" style="color:#2563eb;">event_available</span>
            Appointment Details
        </h2>
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <div>
                <span class="material-symbols-outlined align-middle" style="font-size:1.2em;color:#2563eb;">badge</span>
                <span class="modal-label" style="font-weight:600;">ID:</span>
                <span id="modalApptId" class="modal-value"></span>
            </div>
            <div>
                <span class="material-symbols-outlined align-middle" style="font-size:1.2em;color:#2563eb;">person</span>
                <span class="modal-label" style="font-weight:600;">Customer:</span>
                <span id="modalCustomer" class="modal-value"></span>
            </div>
            <div>
                <span class="material-symbols-outlined align-middle" style="font-size:1.2em;color:#2563eb;">engineering</span>
                <span class="modal-label" style="font-weight:600;">Freelancer:</span>
                <span id="modalFreelancer" class="modal-value"></span>
            </div>
            <div>
                <span class="material-symbols-outlined align-middle" style="font-size:1.2em;color:#2563eb;">calendar_month</span>
                <span class="modal-label" style="font-weight:600;">Date:</span>
                <span id="modalDate" class="modal-value"></span>
            </div>
            <div>
                <span class="material-symbols-outlined align-middle" style="font-size:1.2em;color:#2563eb;">schedule</span>
                <span class="modal-label" style="font-weight:600;">Time:</span>
                <span id="modalTime" class="modal-value"></span>
            </div>
            <div>
                <span class="material-symbols-outlined align-middle" style="font-size:1.2em;color:#2563eb;">check_circle</span>
                <span class="modal-label" style="font-weight:600;">Status:</span>
                <span id="modalStatus" class="status-badge"></span>
            </div>
            <div>
                <span class="material-symbols-outlined align-middle" style="font-size:1.2em;color:#2563eb;">payments</span>
                <span class="modal-label" style="font-weight:600;">Fee Status:</span>
                <span id="modalFeeStatus" class="modal-value"></span>
            </div>
            <div>
                <span class="material-symbols-outlined align-middle" style="font-size:1.2em;color:#2563eb;">note</span>
                <span class="modal-label" style="font-weight:600;">Notes:</span>
                <span id="modalNotes" class="modal-value"></span>
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.view-booking-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const appt = JSON.parse(this.getAttribute('data-appt'));
        document.getElementById('modalApptId').textContent = appt.id;
        document.getElementById('modalCustomer').textContent = (appt.customer?.firstname ?? '') + ' ' + (appt.customer?.lastname ?? '');
        document.getElementById('modalFreelancer').textContent = (appt.freelancer?.firstname ?? '') + ' ' + (appt.freelancer?.lastname ?? '');
        document.getElementById('modalDate').textContent = appt.date;
        document.getElementById('modalTime').textContent = appt.time;
        document.getElementById('modalStatus').textContent = appt.status.replace(/_/g, ' ');
        document.getElementById('modalStatus').style.background = getStatusColor(appt.status);
        document.getElementById('modalFeeStatus').textContent = appt.fee_status;
        document.getElementById('modalNotes').textContent = appt.notes ?? '';
        document.getElementById('bookingDetailsModal').style.display = 'flex';
    });
});

function getStatusColor(status) {
    switch ((status || '').toLowerCase()) {
        case 'pending': return '#fbbf24'; // Yellow
        case 'accepted': return '#2563eb'; // Changed to royal blue
        case 'completed': return '#10b981'; // Green
        case 'canceled': return '#ef4444'; // Red
        case 'declined': return '#6b7280'; // Gray
        case 'no_show_freelancer': return '#eab308'; // Amber/Orange
        case 'no_show_customer': return '#a21caf'; // Purple
        default: return '#6b7280'; // Default Gray
    }
}

// Close modal logic
document.querySelectorAll('.close-modal').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.modal').style.display = 'none';
    });
});
</script>