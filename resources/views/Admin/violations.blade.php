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
                Search
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
                                // Always get user information directly from the user object
                                $role = $violation->user_role ?? 'customer';
                                $userId = $violation->user_id;
                                $firstName = $violation->firstname;
                                $lastName = $violation->lastname;
                                
                                // Determine violation type
                                if (str_contains($violation->status ?? '', 'no_show')) {
                                    $violationType = 'No-Show';
                                    $statusColor = getStatusColor($violation->status);
                                    $statusText = ucfirst(str_replace('_', ' ', $violation->status));
                                } else {
                                    $violationType = 'Late Cancellation';
                                    $statusColor = '#e57373';
                                    $statusText = 'Late Cancellation';
                                }
                                
                                // Get violation count
                                $violationCount = DB::table('violations')
                                    ->where('user_id', $userId)
                                    ->count();
                            @endphp
                           <tr class="violation-row {{ $role }}-violation">
                            <td>{{ $violation->violation_id }}</td>
                            <td>{{ $firstName }} {{ $lastName }}</td>
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
                            <td>{{ $violation->date }} {{ $violation->time }}</td>
                            <td>
                                <span class="violation-type">{{ $violationType }}</span>
                                @if($violationCount > 1)
                                    <span class="violation-count">{{ $violationCount }}x</span>
                                @endif
                            </td>
                            <td class="actions-cell">
                                <div class="action-buttons">
                                    <button class="action-btn warning-btn" onclick="sendWarning({{ $violation->violation_id }}, '{{ $role }}')">
                                        Warning
                                    </button>
                                    <button class="action-btn suspend-btn" onclick="toggleSuspension({{ $violation->violation_id }}, '{{ $role }}')">
                                        Suspend
                                    </button>
                                    <button class="action-btn more-btn" onclick="showMoreOptions({{ $violation->violation_id }}, '{{ $role }}')">
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
            @if($violations->previousPageUrl())
            <a href="{{ $violations->appends(['search' => request('search'), 'section' => 'violations', 'role' => request('role', 'all')])->previousPageUrl() }}" class="category-pagination-btn">
                <i class="fas fa-arrow-left"></i> Previous
            </a>
            @else
            <button class="category-pagination-btn category-btn-disabled" disabled>
                <i class="fas fa-arrow-left"></i> Previous
            </button>
            @endif
            
            @if($violations->hasMorePages())
            <a href="{{ $violations->appends(['search' => request('search'), 'section' => 'violations', 'role' => request('role', 'all')])->nextPageUrl() }}" class="category-pagination-btn">
                Next <i class="fas fa-arrow-right"></i>
            </a>
            @else
            <button class="category-pagination-btn category-btn-disabled" disabled>
                Next <i class="fas fa-arrow-right"></i>
            </button>
            @endif
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
                            <input type="checkbox" id="freelancer-noshow-toggle" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <span>Automatic Warning</span>
                        <label class="switch">
                            <input type="checkbox" id="freelancer-warning-toggle" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <span>Rating Penalty</span>
                        <label class="switch">
                            <input type="checkbox" id="freelancer-rating-toggle" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <span>Auto-Suspension (3+ violations)</span>
                        <label class="switch">
                            <input type="checkbox" id="freelancer-suspension-toggle" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <!-- Add threshold settings -->
                    <div class="threshold-settings">
                        <h5>Violation Thresholds</h5>
                        
                        <div class="setting-config">
                            <label>Warning Threshold</label>
                            <input type="number" id="freelancer-warning-threshold" value="2" min="1" max="10">
                        </div>
                        
                        <div class="setting-config">
                            <label>Restriction Threshold</label>
                            <input type="number" id="freelancer-restriction-threshold" value="3" min="2" max="10">
                        </div>
                        
                        <div class="setting-config">
                            <label>Suspension Threshold</label>
                            <input type="number" id="freelancer-suspension-threshold" value="5" min="3" max="10">
                        </div>
                        
                        <div class="setting-config">
                            <label>Ban Threshold</label>
                            <input type="number" id="freelancer-ban-threshold" value="7" min="4" max="15">
                        </div>
                    </div>
                <div class="setting-config">
                    <label>Suspension Duration (days)</label>
                    <input type="number" id="freelancer-suspension-days" value="7" min="1" max="30">
                </div>
            </div>
            
            <!-- Customer Violation Settings -->
            <div class="violation-settings-card">
                <h4>Customer Violations</h4>
                <div class="settings-group">
                    <div class="setting-item">
                        <span>No-Show Penalties</span>
                        <label class="switch">
                            <input type="checkbox" id="customer-noshow-toggle" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <span>Automatic Warning</span>
                        <label class="switch">
                            <input type="checkbox" id="customer-warning-toggle" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <span>Booking Restrictions</span>
                        <label class="switch">
                            <input type="checkbox" id="customer-booking-toggle" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <span>Auto-Suspension (3+ violations)</span>
                        <label class="switch">
                            <input type="checkbox" id="customer-suspension-toggle" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <!-- Add threshold settings -->
                <div class="threshold-settings">
                    <h5>Violation Thresholds</h5>
                    
                    <div class="setting-config">
                        <label>Warning Threshold</label>
                        <input type="number" id="customer-warning-threshold" value="2" min="1" max="10">
                    </div>
                    
                    <div class="setting-config">
                        <label>Restriction Threshold</label>
                        <input type="number" id="customer-restriction-threshold" value="3" min="2" max="10">
                    </div>
                    
                    <div class="setting-config">
                        <label>Suspension Threshold</label>
                        <input type="number" id="customer-suspension-threshold" value="5" min="3" max="10">
                    </div>
                    
                    <div class="setting-config">
                        <label>Ban Threshold</label>
                        <input type="number" id="customer-ban-threshold" value="7" min="4" max="15">
                    </div>
                </div>
                <div class="setting-config">
                    <label>Suspension Duration (days)</label>
                    <input type="number" id="customer-suspension-days" value="7" min="1" max="30">
                </div>
            </div>
        </div>
        
        <div class="settings-actions">
            <button class="save-settings-btn">Save Settings</button>
            <button class="reset-settings-btn">Reset to Default</button>
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
   window.sendWarning = function(violationId, role) {
    if (confirm('Send a warning to this ' + role + '?')) {
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
                alert('Warning sent successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
};

window.toggleSuspension = function(violationId, role) {
    if (confirm('Toggle suspension for this ' + role + '?')) {
        // AJAX request to toggle suspension
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
   window.showMoreOptions = function(violationId, role) {
    // Get violation data and populate modal
    fetch('/admin/violations/get-details/' + violationId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modal-user-name').textContent = data.user_name;
                
                // Set the role badge with appropriate class
                const roleBadge = document.getElementById('modal-user-role-badge');
                roleBadge.textContent = role.charAt(0).toUpperCase() + role.slice(1);
                roleBadge.className = 'violation-role-badge ' + role;
                
                // Set violation type
                document.getElementById('modal-violation-badge').textContent = data.violation_type;
                
                // Set severity dots based on violation count
                let violationCount = data.violation_count || 1;
                
                // Update severity dots
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
        const settings = {
            freelancer: {
                no_show_penalties: document.getElementById('freelancer-noshow-toggle').checked,
                auto_warning: document.getElementById('freelancer-warning-toggle').checked,
                rating_penalty: document.getElementById('freelancer-rating-toggle').checked,
                auto_suspension: document.getElementById('freelancer-suspension-toggle').checked,
                suspension_days: document.getElementById('freelancer-suspension-days').value,
            
                // New threshold settings
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
           
                // New threshold settings
            warning_threshold: document.getElementById('customer-warning-threshold').value,
            restriction_threshold: document.getElementById('customer-restriction-threshold').value,
            suspension_threshold: document.getElementById('customer-suspension-threshold').value,
            ban_threshold: document.getElementById('customer-ban-threshold').value
            }
        };
        
        // AJAX request to save settings
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
            if (data.success) {
                alert('Settings saved successfully!');
            } else {
                alert('Error: ' + data.message);
            }
        });
    });
});
</script>