<!-- Violations Section -->
<div id="violationsSection" class="details-section" style="display: none;">
    <h2 class="section-title"><span class="material-symbols-outlined align-middle">report_problem</span> Violations & No-Shows</h2>
    
    <!-- Role Filter Tabs -->
    <div class="role-filter-tabs">
        <button class="role-tab active" data-role="all">All Violations</button>
        <button class="role-tab" data-role="freelancer">Freelancer Violations</button>
        <button class="role-tab" data-role="customer">Customer Violations</button>
    </div>
    
    <div class="violation-table-container">
        <!-- Search Bar -->
        <form method="GET" action="{{ route('admin.dashboard') }}" class="table-search-form" style="margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.5rem;">
            <input type="hidden" name="section" value="violations">
            <input type="hidden" name="role" value="{{ request('role', 'all') }}" id="role-filter">
            <div style="position: relative; width: 240px;">
                <span class="material-symbols-outlined"
                    style="color: #2563eb; position: absolute; left: 12px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                    search
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name..."
                    style="padding: 8px 14px 8px 38px; border: 1px solid #ccc; border-radius: 20px; font-size: 15px; width: 100%; background: #f2f2f2;"
                >
            </div>
            <button type="submit" style="padding: 8px 18px; border-radius: 20px; background: #2563eb; color: #fff; border: none; font-size: 15px; cursor: pointer;">
                 <span class="btn-text">Search</span>
                    <span class="btn-spinner" style="display:none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
            </button>
        </form>

        <!-- Violations Table -->
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Violation Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
              <tbody>
                    @foreach($violations as $violation)
                        @php
                            $role = $violation->user_role ?? 'customer';
                            $firstName = $violation->firstname;
                            $lastName = $violation->lastname;
                            $violationCount = $violation->violation_count;
                            
                            // Parse violation types
                            $violationTypes = explode(',', $violation->violation_types);
                            $hasNoShow = in_array('no_show', $violationTypes);
                            $hasLateCancellation = in_array('late_cancellation', $violationTypes);
                            
                            // Determine primary violation type and status
                            if ($hasNoShow && $hasLateCancellation) {
                                $primaryViolationType = 'Mixed Violations';
                                $statusColor = '#e57373';
                                $statusText = 'Multiple Types';
                            } elseif ($hasNoShow) {
                                $primaryViolationType = 'No-Show';
                                $statusColor = '#ffa726';
                                $statusText = 'No Show';
                            } else {
                                $primaryViolationType = 'Late Cancellation';
                                $statusColor = '#e57373';
                                $statusText = 'Late Cancellation';
                            }
                            
                            // Get the most recent violation ID for actions
                            $violationIds = explode(',', $violation->violation_ids);
                            $latestViolationId = end($violationIds);
                            
                            // Parse appointment dates and times
                            $appointmentDates = explode(',', $violation->appointment_dates ?? '');
                            $appointmentTimes = explode(',', $violation->appointment_times ?? '');
                            $latestDate = !empty($appointmentDates[0]) ? $appointmentDates[0] : 'N/A';
                            $latestTime = !empty($appointmentTimes[0]) ? $appointmentTimes[0] : '';
                        @endphp
                        
                        <tr class="violation-row {{ $role }}-violation">
                            <td>{{ $violation->user_id }}</td>
                            <td>
                                <div class="user-name-cell">
                                    <span class="user-name">{{ $firstName }} {{ $lastName }}</span>
                                    @if($violationCount > 1)
                                        <span class="violation-count-badge">{{ $violationCount }} violations</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="role-badge {{ $role }}">
                                    {{ ucfirst($role) }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge" style="background:{{ $statusColor }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <span class="latest-date">{{ $latestDate }}</span>
                                    @if($latestTime)
                                        <span class="latest-time">{{ $latestTime }}</span>
                                    @endif
                                    @if($violationCount > 1)
                                        <small class="multiple-dates">+{{ $violationCount - 1 }} more</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="violation-type-cell">
                                    <span class="violation-type">{{ $primaryViolationType }}</span>
                                    @if($violationCount > 1)
                                        <div class="violation-breakdown">
                                            @if($hasNoShow)
                                                <span class="violation-mini-badge no-show">No-Show</span>
                                            @endif
                                            @if($hasLateCancellation)
                                                <span class="violation-mini-badge cancellation">Late Cancel</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="actions-cell">
                                <div class="action-buttons">
                                    <button class="action-btn warning-btn" 
                                            onclick="sendWarning({{ $latestViolationId }}, '{{ $role }}', this)"
                                            title="Send Warning">
                                        Warning
                                    </button>
                                    <button class="action-btn suspend-btn" 
                                            onclick="toggleSuspension({{ $latestViolationId }}, '{{ $role }}', this)"
                                            title="Toggle Suspension">
                                        Suspend
                                    </button>
                                    <button class="action-btn more-btn" 
                                            onclick="showMoreOptions({{ $latestViolationId }}, '{{ $role }}', {{ $violationCount }}, '{{ $firstName }} {{ $lastName }}')"
                                            title="More Options">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="category-pagination-container">
          {{ $violations->appends(request()->except('violationPage'))->links() }}
        </div>
    </div>

    <!-- Violation Settings Section -->
    <div class="violation-settings-container mt-5">
        <h3 class="settings-title">Violation Settings</h3>
        
        <div class="violation-settings-grid">
            <!-- Freelancer Violation Settings -->
           <div class="violation-settings-card">
                <h4>Freelancer Violations</h4>
                <div class="settings-group">
                    <div class="setting-item">
                        <span>No-Show Penalties</span>
                        <label class="switch">
                            <input type="checkbox" id="freelancer-noshow-toggle" 
                                {{ $freelancerSettings->no_show_penalties ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <span>Automatic Warning</span>
                        <label class="switch">
                            <input type="checkbox" id="freelancer-warning-toggle" 
                                {{ $freelancerSettings->auto_warning ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <span>Booking Restrictions</span>
                        <label class="switch">
                            <input type="checkbox" id="freelancer-booking-toggle" 
                                {{ $freelancerSettings->booking_restrictions ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <span>Auto-Suspension (5+ violations)</span>
                        <label class="switch">
                            <input type="checkbox" id="freelancer-suspension-toggle" 
                                {{ $freelancerSettings->auto_suspension ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                
                <div class="threshold-settings">
                    <h5>Violation Thresholds</h5>
                    
                    <div class="setting-config">
                        <label>Warning Threshold</label>
                        <input type="number" id="freelancer-warning-threshold" 
                            value="{{ $freelancerSettings->warning_threshold }}" min="1" max="10">
                    </div>
                    
                    <div class="setting-config">
                        <label>Restriction Threshold</label>
                        <input type="number" id="freelancer-restriction-threshold" 
                            value="{{ $freelancerSettings->restriction_threshold }}" min="2" max="10">
                    </div>
                    
                    <div class="setting-config">
                        <label>Suspension Threshold</label>
                        <input type="number" id="freelancer-suspension-threshold" 
                            value="{{ $freelancerSettings->suspension_threshold }}" min="3" max="10">
                    </div>
                    
                    <div class="setting-config">
                        <label>Ban Threshold</label>
                        <input type="number" id="freelancer-ban-threshold" 
                            value="{{ $freelancerSettings->ban_threshold }}" min="4" max="15">
                    </div>
                </div>
                
                <div class="setting-config">
                    <label>Suspension Duration (days)</label>
                    <input type="number" id="freelancer-suspension-days" 
                        value="{{ $freelancerSettings->suspension_days }}" min="1" max="30">
                </div>
            </div>

            <!-- Same for customer settings - replace hardcoded values with database values -->
            <div class="violation-settings-card">
                <h4>Customer Violations</h4>
                <div class="settings-group">
                    <div class="setting-item">
                        <span>No-Show Penalties</span>
                        <label class="switch">
                            <input type="checkbox" id="customer-noshow-toggle" 
                                {{ $customerSettings->no_show_penalties ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <span>Automatic Warning</span>
                        <label class="switch">
                            <input type="checkbox" id="customer-warning-toggle" 
                                {{ $customerSettings->auto_warning ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <span>Booking Restrictions</span>
                        <label class="switch">
                            <input type="checkbox" id="customer-booking-toggle" 
                                {{ $customerSettings->booking_restrictions ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <span>Auto-Suspension (5+ violations)</span>
                        <label class="switch">
                            <input type="checkbox" id="customer-suspension-toggle" 
                                {{ $customerSettings->auto_suspension ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div class="threshold-settings">
                    <h5>Violation Thresholds</h5>
                    
                    <div class="setting-config">
                        <label>Warning Threshold</label>
                        <input type="number" id="customer-warning-threshold" 
                            value="{{ $customerSettings->warning_threshold }}" min="1" max="10">
                    </div>
                    
                    <div class="setting-config">
                        <label>Restriction Threshold</label>
                        <input type="number" id="customer-restriction-threshold" 
                            value="{{ $customerSettings->restriction_threshold }}" min="2" max="10">
                    </div>
                    
                    <div class="setting-config">
                        <label>Suspension Threshold</label>
                        <input type="number" id="customer-suspension-threshold" 
                            value="{{ $customerSettings->suspension_threshold }}" min="3" max="10">
                    </div>
                    
                    <div class="setting-config">
                        <label>Ban Threshold</label>
                        <input type="number" id="customer-ban-threshold" 
                            value="{{ $customerSettings->ban_threshold }}" min="4" max="15">
                    </div>
                </div>
                
                <div class="setting-config">
                    <label>Suspension Duration (days)</label>
                    <input type="number" id="customer-suspension-days" 
                        value="{{ $customerSettings->suspension_days }}" min="1" max="30">
                </div>
            </div>
        
        <div class="settings-actions">
            <button class="save-settings-btn">
                <span class="btn-text">Save Settings</span>
                <span class="btn-spinner" style="display:none;">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
             </button>
            <button class="reset-settings-btn">
                <span class="btn-text">Reset to Default</span>
                <span class="btn-spinner" style="display:none;">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </button>
            </div>
        </div>
    </div>
   </div>
   </div>
</div>

<!-- More Options Modal -->
<div id="violationActionModal" class="violation-modal">
    <div class="violation-modal-content">
        <div class="violation-modal-header">
            <h3>
                <span class="material-symbols-outlined">gavel</span>
                Violation Actions
            </h3>
            <span class="violation-modal-close">&times;</span>
        </div>
        
        <div class="violation-user-card">
            <div class="violation-user-info">
                <div class="violation-user-details">
                    <p><strong>User:</strong> <span id="modal-user-name"></span></p>
                    <div class="violation-badge-container">
                        <span class="violation-role-badge" id="modal-user-role-badge">Freelancer</span>
                        <span class="violation-type-badge" id="modal-violation-badge">No-Show</span>
                    </div>
                </div>
                <div class="violation-severity-indicator">
                    <div class="severity-label">Severity</div>
                    <div class="severity-dots">
                        <span class="severity-dot active"></span>
                        <span class="severity-dot"></span>
                        <span class="severity-dot"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="violation-action-section">
            <h4>
                <span class="material-symbols-outlined">assignment</span>
                Select Action
            </h4>
            
            <div class="violation-action-grid">
                <div class="violation-action-option">
                    <input type="radio" name="action" id="action-warning" value="warning">
                    <label for="action-warning">
                        <span class="violation-action-icon warning">
                            <span class="material-symbols-outlined">warning</span>
                        </span>
                        <span class="violation-action-text">
                            <strong>Send Warning</strong>
                            <span class="violation-action-desc">Notify user about violation</span>
                        </span>
                    </label>
                </div>
                
                <div class="violation-action-option">
                    <input type="radio" name="action" id="action-fee" value="fee">
                    <label for="action-fee">
                        <span class="violation-action-icon fee">
                            <span class="material-symbols-outlined">payments</span>
                        </span>
                        <span class="violation-action-text">
                            <strong>Apply Fee</strong>
                            <span class="violation-action-desc">Charge penalty fee</span>
                        </span>
                    </label>
                </div>
                
                <div class="violation-action-option">
                    <input type="radio" name="action" id="action-suspend" value="suspend">
                    <label for="action-suspend">
                        <span class="violation-action-icon suspend">
                            <span class="material-symbols-outlined">block</span>
                        </span>
                        <span class="violation-action-text">
                            <strong>Suspend Account</strong>
                            <span class="violation-action-desc">Temporary account suspension</span>
                        </span>
                    </label>
                </div>
                
                <div class="violation-action-option">
                    <input type="radio" name="action" id="action-restrict" value="restrict">
                    <label for="action-restrict">
                        <span class="violation-action-icon restrict">
                            <span class="material-symbols-outlined">lock</span>
                        </span>
                        <span class="violation-action-text">
                            <strong>Restrict Activities</strong>
                            <span class="violation-action-desc">Limit user functionality</span>
                        </span>
                    </label>
                </div>
                
                <div class="violation-action-option">
                    <input type="radio" name="action" id="action-ban" value="ban">
                    <label for="action-ban">
                        <span class="violation-action-icon ban">
                            <span class="material-symbols-outlined">cancel</span>
                        </span>
                        <span class="violation-action-text">
                            <strong>Ban Account</strong>
                            <span class="violation-action-desc">Permanent account ban</span>
                        </span>
                    </label>
                </div>
            </div>
            
            <!-- Action details sections -->
            <div class="violation-action-details" id="fee-details">
                <div class="violation-detail-group">
                    <label>Penalty Fee Amount:</label>
                    <div class="violation-input-group">
                        <span class="violation-input-prefix">₱</span>
                        <input type="number" id="penalty-fee" placeholder="Enter amount" min="50" step="50">
                    </div>
                </div>
            </div>
            
            <div class="violation-action-details" id="suspend-details">
                <div class="violation-detail-group">
                    <label>Suspension Duration:</label>
                    <div class="violation-input-group">
                        <input type="number" id="suspension-days" value="7" min="1" max="30">
                        <span class="violation-input-suffix">days</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="violation-notes-section">
            <label for="action-notes">
                <span class="material-symbols-outlined">description</span>
                Admin Notes:
            </label>
            <textarea id="action-notes" placeholder="Add notes about this action (will be visible to the user)..."></textarea>
        </div>
        
        <div class="violation-modal-actions">
            <button id="cancel-action-btn" class="violation-btn-secondary">
                <span class="material-symbols-outlined">close</span>
                Cancel
            </button>
            <button id="apply-action-btn" class="violation-btn-primary">
                <span class="material-symbols-outlined">check_circle</span>
                Apply Action
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Role filter tabs functionality
    const roleTabs = document.querySelectorAll('.role-tab');
    const roleFilter = document.getElementById('role-filter');
    const violationRows = document.querySelectorAll('.violation-row');
    
    roleTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active tab
            roleTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Update filter value
            const role = this.dataset.role;
            roleFilter.value = role;
            
            // Filter table rows
            if (role === 'all') {
                violationRows.forEach(row => row.style.display = 'table-row');
            } else {
                violationRows.forEach(row => {
                    if (row.classList.contains(role + '-violation')) {
                        row.style.display = 'table-row';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        });
    });
    
    // Modal functionality - MOVED UP FROM NESTED DOMContentLoaded
    const modal = document.getElementById('violationActionModal');
    const closeBtn = document.querySelector('.violation-modal-close');
    const cancelBtn = document.getElementById('cancel-action-btn');
    
    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    cancelBtn.addEventListener('click', () => modal.style.display = 'none');
    
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Show conditional fields based on action selection
    const actionRadios = document.querySelectorAll('input[name="action"]');
    actionRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.violation-action-details').forEach(el => {
                el.style.display = 'none';
            });
            
            if (this.value === 'fee') {
                document.getElementById('fee-details').style.display = 'block';
            } else if (this.value === 'suspend') {
                document.getElementById('suspend-details').style.display = 'block';
            }
        });
    });
   
    // Action button handlers
   window.sendWarning = function(violationId, role, button) {
    if (confirm('Send a warning to this ' + role + '?')) {
       showSpinnerOnButton(button);
        // AJAX request to send warning
        fetch('/admin/violations/warning', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                violation_id: violationId,  // Changed from appointment_id
                role: role
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                restoreButton(button, 'Warning');
                alert('Warning sent successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
};

window.toggleSuspension = function(violationId, role, button) {
    if (confirm('Toggle suspension for this ' + role + '?')) {
        // AJAX request to toggle suspension
        showSpinnerOnButton(button);
        fetch('/admin/violations/suspend', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                violation_id: violationId,  // Changed from appointment_id
                role: role
            })
        })
        .then(response => response.json())
        .then(data => {
            restoreButton(button, 'Suspend');
            if (data.success) {
                alert(data.message);
                // Refresh the page to update UI
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
};
    
    // IMPORTANT: Define showMoreOptions in the global scope
   window.showMoreOptions = function(violationId, role, violationCount, userName) {
    // Get violation data and populate modal
    fetch('/admin/violations/get-details/' + violationId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Use the passed userName or fallback to API data
                document.getElementById('modal-user-name').textContent = userName || data.user_name;
                
                // Set the role badge with appropriate class
                const roleBadge = document.getElementById('modal-user-role-badge');
                roleBadge.textContent = role.charAt(0).toUpperCase() + role.slice(1);
                roleBadge.className = 'violation-role-badge ' + role;
                
                // Set violation type with count
                const violationBadge = document.getElementById('modal-violation-badge');
                if (violationCount > 1) {
                    violationBadge.textContent = `${data.violation_type} (${violationCount} total)`;
                } else {
                    violationBadge.textContent = data.violation_type;
                }
                
                // Set severity dots based on violation count
                const dots = document.querySelectorAll('.severity-dot');
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index < Math.min(violationCount, 3));
                });
                
                // Store violation ID for use when submitting the form
                document.getElementById('apply-action-btn').dataset.violationId = violationId;
                document.getElementById('apply-action-btn').dataset.role = role;
                
                // Show the modal
                modal.style.display = 'block';
            } else {
                alert('Error: ' + data.message);
            }
        });
};
    
    // Apply action button handler - keeping the same logic but updating selectors
   document.getElementById('apply-action-btn').addEventListener('click', function() {
    const violationId = this.dataset.violationId;
    const role = this.dataset.role;
    const action = document.querySelector('input[name="action"]:checked')?.value;
    const notes = document.getElementById('action-notes').value;
     const button = this;
    showSpinnerOnButton(button);
    if (!action) {
        alert('Please select an action');
        return;
    }
    
    let actionData = {
        violation_id: violationId,  // Changed from appointment_id to violation_id
        role: role,
        action: action,
        notes: notes
    };
    
    // Add action-specific data
    if (action === 'fee') {
        actionData.fee_amount = document.getElementById('penalty-fee').value;
    } else if (action === 'suspend') {
        actionData.suspension_days = document.getElementById('suspension-days').value;
    }
    
    // AJAX request to apply action
    fetch('/admin/violations/apply-action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(actionData)
    })
    .then(response => response.json())
    .then(data => {
        restoreButton(button, 'Apply Action');
        if (data.success) {
            alert('Action applied successfully!');
            modal.style.display = 'none';
            // Refresh the page to update UI
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
});
    
    // Settings save functionality
  document.querySelector('.save-settings-btn').addEventListener('click', function() {
    const button = this;
    showSpinnerOnButton(button);
    
    const settings = {
        freelancer: {
            no_show_penalties: document.getElementById('freelancer-noshow-toggle').checked,
            auto_warning: document.getElementById('freelancer-warning-toggle').checked,
             booking_restrictions: document.getElementById('freelancer-booking-toggle').checked,
            auto_suspension: document.getElementById('freelancer-suspension-toggle').checked,
            suspension_days: document.getElementById('freelancer-suspension-days').value,
            warning_threshold: document.getElementById('freelancer-warning-threshold').value,
            restriction_threshold: document.getElementById('freelancer-restriction-threshold').value,
            suspension_threshold: document.getElementById('freelancer-suspension-threshold').value,
            ban_threshold: document.getElementById('freelancer-ban-threshold').value
        },
        customer: {
            no_show_penalties: document.getElementById('customer-noshow-toggle').checked,
            auto_warning: document.getElementById('customer-warning-toggle').checked,
            booking_restrictions: document.getElementById('customer-booking-toggle').checked,
            auto_suspension: document.getElementById('customer-suspension-toggle').checked,
            suspension_days: document.getElementById('customer-suspension-days').value,
            warning_threshold: document.getElementById('customer-warning-threshold').value,
            restriction_threshold: document.getElementById('customer-restriction-threshold').value,
            suspension_threshold: document.getElementById('customer-suspension-threshold').value,
            ban_threshold: document.getElementById('customer-ban-threshold').value
        }
    };
    
    fetch('/admin/violation-settings', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(settings)
    })
    .then(response => response.json())
    .then(data => {
        restoreButton(button, 'Save Settings');
        if (data.success) {
            alert('Settings saved successfully!');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        restoreButton(button, 'Save Settings');
        alert('Network error: ' + error.message);
    });
});

const resetBtn = document.querySelector('.reset-settings-btn');
if (resetBtn) {
    resetBtn.addEventListener('click', function() {
        // Reset to default values (adjust as needed)
        document.getElementById('freelancer-noshow-toggle').checked = true;
        document.getElementById('freelancer-warning-toggle').checked = true;
      document.getElementById('freelancer-booking-toggle').checked = true;
        document.getElementById('freelancer-suspension-toggle').checked = true;
        document.getElementById('freelancer-suspension-days').value = 7;
        document.getElementById('freelancer-warning-threshold').value = 2;
        document.getElementById('freelancer-restriction-threshold').value = 3;
        document.getElementById('freelancer-suspension-threshold').value = 5;
        document.getElementById('freelancer-ban-threshold').value = 7;

        document.getElementById('customer-noshow-toggle').checked = true;
        document.getElementById('customer-warning-toggle').checked = true;
        document.getElementById('customer-booking-toggle').checked = true;
        document.getElementById('customer-suspension-toggle').checked = true;
        document.getElementById('customer-suspension-days').value = 7;
        document.getElementById('customer-warning-threshold').value = 2;
        document.getElementById('customer-restriction-threshold').value = 3;
        document.getElementById('customer-suspension-threshold').value = 5;
        document.getElementById('customer-ban-threshold').value = 7;
    });
}
});

</script>