

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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- FullCalendar CSS -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
   <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"rel="stylesheet"/>
 
 
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
        <a href="#profileSection" id="profileLink" class="active"><span class="material-symbols-outlined">person</span>Profile</a>
      </li>
      <li>
        <a href="#dashboardSection" id="dashboardLink"><span class="material-symbols-outlined">dashboard</span>Dashboard</a>
      </li>
      <li>
        <a href="#appointmentCalendar" id="appointmentLink"><span class="material-symbols-outlined">calendar_today</span>Appointment</a>
      </li>
      <li>
        <a href="#postContainer" id="postLink"><span class="material-symbols-outlined">person</span>Post</a>
      
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
                        <p>{{ $notification->data['message'] ?? 'No message available' }}</p>
                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                        <button class="mark-single-read" data-id="{{ $notification->id }}" {{ $notification->read_at ? 'disabled' : '' }}>
                            {{ $notification->read_at ? 'Read' : 'Mark as Read' }}
                        </button>
                    </li>
                @empty
                    <li>No new notifications</li>
                @endforelse
        </ul>
        <button id="markAllAsRead" class="mark-read-btn">Mark All as Read</button>
    </div>
      </div>
      <div class="message">
        <span class="material-symbols-outlined">email</span>
        <span class="count">5</span>
      </div>
      <!-- Profile Picture in Header -->
       <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/defaultprofile.jpg') }}"  alt="User Avatar" class="avatar cursor-pointer" id="profilePic" />
      <div class="user-info">
        <span class="user-name">{{ Auth::user()->firstname }}</span>
        <p class="freelancer">{{ Auth::user()->role }}</p>
    </div>
    
    </div>
  </header>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const notificationIcon = document.getElementById('notificationIcon');
        const notificationContainer = document.getElementById('notificationContainer');
        const markAllAsRead = document.getElementById('markAllAsRead');
        const notificationCount = document.getElementById('notificationCount');

        // Toggle notification dropdown
        notificationIcon.addEventListener('click', function () {
            notificationContainer.style.display =
                notificationContainer.style.display === 'none' ? 'block' : 'none';
        });

        // Mark all notifications as read
        markAllAsRead.addEventListener('click', function () {
            fetch('{{ route("freelancer.notifications.markAllAsRead") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notificationCount.textContent = '0';
                        document.querySelectorAll('.notification-container ul li').forEach(item => {
                        item.classList.add('read'); // Add "read" class to all notifications
                    });
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        // Mark a single notification as read
        document.querySelectorAll('.mark-single-read').forEach(button => {
            button.addEventListener('click', function () {
                const notificationId = this.getAttribute('data-id');
                fetch(`/freelancer/notifications/${notificationId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.parentElement.classList.add('read');// Remove the notification from the list
                            notificationCount.textContent = data.unread_count;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
</script>