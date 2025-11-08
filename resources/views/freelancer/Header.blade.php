
   <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Freelancer Dashboard</title>
  
  <link  rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"/>
  <link rel="stylesheet"href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
  <link rel="stylesheet" href="{{asset ('css/NewFreelancer.css')}}" />
   <link
      href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
      rel="stylesheet"
    />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- FullCalendar CSS -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
   <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"rel="stylesheet"/>
 <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<!-- Add this in the <head> section of your HTML -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>  
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
 
 <!-- Tailwind CSS (Optional for better styling) -->
 <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-header">
        <h2 class="logo-header">
            MinglaGawa
        </h2>          
    </div>
    <ul class="sidebar-links">
      
      <li>
        <a href="#dashboardSection" id="dashboardLink"><span class="material-symbols-outlined">dashboard</span>Dashboard</a>
      </li>
      <li>
        <a href="#profileSection" id="profileLink" class="active"><span class="material-symbols-outlined">person</span>Profile</a>
      </li>
      <li>
        <a href="#appointmentCalendar" id="appointmentLink"><span class="material-symbols-outlined">calendar_today</span>Appointment</a>
      </li>
      <li>
        <a href="#postContainer" id="postLink"><span class="material-symbols-outlined">post</span>Post</a>
      
        </li>
        <li>
        <a href="#rescheduleSection" id="rescheduleLink" class="sidebar-link">
            <span class="material-symbols-outlined">schedule</span>Schedule
        </a>
    </li>
     <li>
    <a href="#revenueSection" id="revenueLink" class="sidebar-link">
        <span class="material-symbols-outlined">account_balance_wallet</span>My Wallet
    </a>
  </li>
       <li>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" id ="logout-button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="material-symbols-outlined">logout</span>Logout
            </a>
      </li>
    </ul>
  </aside>

  <header class="top-nav">
    <div class="search-area">
      <span class="material-symbols-outlined">search</span>
      <input type="text" placeholder="Search..." />
    </div>
   
    <div class="user-area">
      <div class="notification">
      <span class="material-symbols-outlined" id="notificationIcon">notifications</span>
        <span class="count" id="notificationCount">{{ $unreadNotifications->count() }}</span>
       
       <!-- Notification Dropdown -->
<div class="notification-container" id="notificationContainer" style="display: none;">
    <h3>Notifications</h3>
    <ul>
       @forelse($notifications as $notification)
    <li class="{{ $notification->read_at ? 'read' : '' }}">
        <div class="notification-item">
            @php
                // Get notification type
                $notificationType = class_basename($notification->type);
                
                // Set icon and color based on notification type
                $iconClass = 'default';
                $iconSymbol = 'notifications';
                
                // Parse notification title and icon
                if (str_contains($notification->type, 'CategoryRequestProcessedNotification')) {
                    $title = 'Category Request ' . ucfirst($notification->data['status'] ?? 'Processed');
                    $iconSymbol = $notification->data['status'] == 'approved' ? 'check_circle' : 'cancel';
                    $iconClass = $notification->data['status'] == 'approved' ? 'success' : 'danger';
                } 
                elseif (str_contains($notification->type, 'AppointmentNotification')) {
                    $title = 'Appointment Update';
                    $iconSymbol = 'calendar_today';
                    $iconClass = 'appointment';
                } 
                elseif (str_contains($notification->type, 'PostApprovalNotification')) {
                    $title = 'Post ' . ucfirst($notification->data['status'] ?? 'Updated');
                    $iconSymbol = $notification->data['status'] == 'approved' ? 'check_circle' : 'cancel';
                    $iconClass = $notification->data['status'] == 'approved' ? 'success' : 'danger';
                }
                elseif (str_contains($notification->type, 'FinalPaymentReceivedNotification')) {
                    $title = 'Payment Received';
                    $iconSymbol = 'payments';
                    $iconClass = 'payment';
                }
                 elseif (str_contains($notification->type, 'WithdrawalStatusNotification')) {
                    $title = 'Withdrawal ' . ucfirst($notification->data['status'] ?? 'Updated');
                    $iconSymbol = 'account_balance_wallet';
                    $iconClass = $notification->data['status'] == 'completed' ? 'success' : 'info';
                }
               elseif (str_contains($notification->type, 'ViolationNotification')) {
                    $title = $notification->data['title'] ?? 'Violation Notice';
                    $message = $notification->data['message'] ?? '';
                    $iconSymbol = match($notification->data['action_type'] ?? '') {
                        'warning' => 'warning',
                        'suspension' => 'block',
                        'fee' => 'payments',
                        'ban' => 'cancel',
                        'restrict' => 'not_interested',
                        default => 'error'
                    };
                    $iconClass = in_array($notification->data['action_type'] ?? '', ['suspension', 'ban']) ? 'danger' : 'warning';
                }
                else {
                    $title = 'Notification';
                }
                
                // Get message content
                $message = $notification->data['message'] ?? 'You have a new notification';
            @endphp

            <div class="notification-icon {{ $iconClass }}">
                <span class="material-symbols-outlined">{{ $iconSymbol }}</span>
            </div>
            <div class="notification-content">
                <strong>{{ $title }}</strong>
                
                <div class="notification-text-container">
                    <!-- Preview version (truncated) -->
                    <p class="notification-preview">
                        {{ \Illuminate\Support\Str::limit($message, 60) }}
                    </p>
                    
                    <!-- Full content version (hidden initially) -->
                    <div class="notification-full-content" style="display: none;">
                        <p>{{ $message }}</p>
                        
                        {{-- Display additional details if available --}}
                        @if(str_contains($notification->type, 'CategoryRequestProcessedNotification') && isset($notification->data['admin_notes']))
                            <p class="admin-note">{{ $notification->data['admin_notes'] }}</p>
                        @endif
                        
                        @if(str_contains($notification->type, 'FinalPaymentReceivedNotification'))
                            @if(isset($notification->data['amount']))
                                <p class="payment-details">
                                    Amount: ₱{{ number_format($notification->data['amount'], 2) }}
                                </p>
                            @endif
                            
                            @if(isset($notification->data['date']) && isset($notification->data['time']))
                                <p class="appointment-details">
                                    Service Date: {{ $notification->data['date'] }} at {{ $notification->data['time'] }}
                                </p>
                            @endif
                        @endif
                    </div>
                    
                    <!-- Toggle button - shows for ALL notification types -->
                    <button class="toggle-content-btn" aria-expanded="false" data-notification-id="{{ $notification->id }}">
                        <span class="expand-text">Read more</span>
                        <span class="collapse-text" style="display: none;">Show less</span>
                    </button>
                </div>
            </div>
        </div>
        
        <small>{{ $notification->created_at->diffForHumans() }}</small>
        <button class="mark-single-read" data-id="{{ $notification->id }}" {{ $notification->read_at ? 'disabled' : '' }}>
            {{ $notification->read_at ? 'Read' : 'Mark as Read' }}
        </button>
    </li>
@empty
    <li class="empty-notification">No notifications</li>
@endforelse
    </ul>
    @if($unreadNotifications->count() > 0)
        <button id="markAllAsRead" class="mark-read-btn">Mark All as Read</button>
    @endif
</div>
      </div>
      
      <!-- Profile Picture in Header -->
       <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/defaultprofile.jpg') }}"  alt="User Avatar" class="avatar cursor-pointer" id="profilePic" />
      <div class="user-info">
        <span class="user-name">{{ Str::title(Auth::user()->firstname) }}</span>
        <p class="freelancer">{{ Str::title(Auth::user()->role) }}</p>
    </div>
      
    </div>
  </header>



  <script>
   document.addEventListener('DOMContentLoaded', function () {
    const notificationIcon = document.getElementById('notificationIcon');
    const notificationContainer = document.getElementById('notificationContainer');
    const markAllAsRead = document.getElementById('markAllAsRead');
    const notificationCount = document.getElementById('notificationCount');

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!notificationIcon.contains(event.target) && !notificationContainer.contains(event.target)) {
            notificationContainer.style.display = 'none';
        }
    });

    // Toggle notification dropdown
    notificationIcon.addEventListener('click', function () {
        notificationContainer.style.display =
            notificationContainer.style.display === 'none' ? 'block' : 'none';
    });

    // Mark all notifications as read
    if (markAllAsRead) {
        markAllAsRead.addEventListener('click', function () {
            fetch('{{ route("freelancer.notifications.markAllAsRead") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI to reflect all notifications as read
                    notificationCount.textContent = '0';
                    
                    // Hide the notification counter if it's zero
                    if (notificationCount.textContent === '0') {
                        notificationCount.style.display = 'none';
                    }
                    
                    // Mark all notifications as read in the UI
                    document.querySelectorAll('.notification-container ul li').forEach(item => {
                        item.classList.add('read');
                        
                        // Update button text
                        const button = item.querySelector('.mark-single-read');
                        if (button) {
                            button.textContent = 'Read';
                            button.disabled = true;
                        }
                    });
                    
                    // Hide the "Mark All as Read" button
                    markAllAsRead.style.display = 'none';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Mark a single notification as read
    document.querySelectorAll('.mark-single-read').forEach(button => {
        button.addEventListener('click', function () {
            const notificationId = this.getAttribute('data-id');
            const notificationItem = this.closest('li');
            
            // Log the notification ID for debugging
            console.log('Marking notification as read:', notificationId);
            
            fetch(`/freelancer/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server responded with ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Mark the specific notification as read
                    notificationItem.classList.add('read');
                    this.textContent = 'Read';
                    this.disabled = true;
                    
                    // Update the notification counter
                    notificationCount.textContent = data.unread_count;
                    
                    // Hide the notification counter if it's zero
                    if (data.unread_count === 0) {
                        notificationCount.style.display = 'none';
                        
                        // Also hide the "Mark All as Read" button
                        if (markAllAsRead) {
                            markAllAsRead.style.display = 'none';
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
                alert('Could not mark notification as read. Please try again.');
            });
        });
    });
});

// Add this to your existing JavaScript code
document.addEventListener('DOMContentLoaded', function() {
    // Set up expandable notifications
    document.querySelectorAll('.toggle-content-btn').forEach(button => {
        const container = button.closest('.notification-text-container');
        const preview = container.querySelector('.notification-preview');
        const fullContent = container.querySelector('.notification-full-content');
        
        // Get full text and preview text
        const fullText = fullContent.textContent.trim();
        const previewText = preview.textContent.trim();
        
        // Only show toggle button if content is actually truncated
        if (previewText.length < fullText.length) {
            button.style.display = 'inline-block';
        } else {
            button.style.display = 'none';
        }
        
        // Add click event handler
        button.addEventListener('click', function(e) {
            e.stopPropagation(); // Stop event propagation
            e.preventDefault(); // Prevent default action
            
            const expandText = this.querySelector('.expand-text');
            const collapseText = this.querySelector('.collapse-text');
            
            // Toggle visibility
            if (this.getAttribute('aria-expanded') === 'false') {
                // Expand
                preview.style.display = 'none';
                fullContent.style.display = 'block';
                expandText.style.display = 'none';
                collapseText.style.display = 'inline';
                this.setAttribute('aria-expanded', 'true');
                
                // Force container recalculation to fit content
                const parentLi = this.closest('li');
                if (parentLi) {
                    parentLi.style.height = 'auto';
                }
                
                // Store expanded state
                localStorage.setItem('notification_' + this.dataset.notificationId + '_expanded', 'true');
            } else {
                // Collapse
                preview.style.display = 'block';
                fullContent.style.display = 'none';
                expandText.style.display = 'inline';
                collapseText.style.display = 'none';
                this.setAttribute('aria-expanded', 'false');
                
                // Store collapsed state
                localStorage.setItem('notification_' + this.dataset.notificationId + '_expanded', 'false');
            }
            
            // Ensure notification container updates its scroll height
            const notificationContainer = document.getElementById('notificationContainer');
            if (notificationContainer) {
                setTimeout(() => {
                    notificationContainer.style.maxHeight = '80vh'; // Force recalculation
                }, 10);
            }
        });
        
        // Restore expanded state from localStorage if available
        const notificationId = button.dataset.notificationId;
        if (localStorage.getItem('notification_' + notificationId + '_expanded') === 'true') {
            // Simulate click to expand
            button.click();
        }
    });
});
</script>