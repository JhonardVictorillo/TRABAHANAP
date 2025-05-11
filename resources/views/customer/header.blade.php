<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel ="stylesheet" href="{{asset ('css/customerNotif.css')}}" />
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Pacifico&family=Inter:wght@400;500;600&family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
      rel="stylesheet"
    />
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: "#118f39",
              secondary: "#64748B",
            },
            borderRadius: {
              none: "0px",
              sm: "4px",
              DEFAULT: "8px",
              md: "12px",
              lg: "16px",
              xl: "20px",
              "2xl": "24px",
              "3xl": "32px",
              full: "9999px",
              button: "8px",
            },
            fontFamily: {
              inter: ["Inter", "sans-serif"],
              poppins: ["Poppins", "sans-serif"],
            },
          },
        },
      };
    </script>
    <style>
      :where([class^="ri-"])::before { content: "\f3c2"; }
      .search-input:focus {
      outline: none;
      box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
      }
      .service-card:hover {
      transform: translateY(-2px);
      transition: all 0.2s ease-in-out;
      }
    </style>
  </head>

  
     
  <body class="bg-[#F8F9FA] font-inter">
    <header class="sticky top-0 z-50 bg-white shadow-sm">
      <div class="flex items-center justify-between px-8 h-16">
      <a href="/" class="font-poppins text-2xl font-extrabold">
          <span class="text-[#118f39]">Mingla</span> 
          <span class="text-[#4CAF50]">Gawa</span> 
        </a>
        <div class="flex items-center flex-1 max-w-xl mx-8">
          <div class="relative w-full">
          <form action="{{ route('search') }}" method="GET" style="display: flex; align-items: center; width: 100%;">
            <input
              type="text"
              name="q" 
              class="search-bar w-full h-10 pl-10 pr-4 text-sm bg-gray-50 border-none !rounded-button"
              placeholder="Search for services or freelancers..."
              value="{{ request('q') }}"
            />
            <div
              class="absolute left-3 top-0 w-4 h-10 flex items-center justify-center text-gray-400"
            >
              <i class="ri-search-line"></i>
            </div>
            </form>
          </div>
        </div>
        <div class="flex items-center gap-6">
        <div class="relative inline-block text-left">
    <!-- Notification Icon -->
    <button id="notification-icon" class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-primary">
        <div class="relative w-6 h-6 flex items-center justify-center">
            <i class="ri-notification-line"></i>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </div>
        <span>Notifications</span>
    </button>

    <!-- Notification Dropdown -->
    <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg z-50">
        <div class="p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-700">Notifications</h3>
        </div>
        <ul id="notification-list" class="max-h-64 overflow-y-auto">
            @foreach(auth()->user()->notifications as $notification)
                <li class="flex items-start justify-between px-4 py-3 border-b hover:bg-gray-50 {{ $notification->read_at ? 'bg-gray-100' : '' }}">
                    <div>
                        <p class="text-sm {{ $notification->read_at ? 'text-gray-500' : 'text-gray-600' }}">
                            {{ $notification->data['message'] }}
                        </p>
                        <p class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$notification->read_at)
                        <button
                            class="text-sm text-primary hover:underline mark-as-read"
                            data-id="{{ $notification->id }}"
                        >
                            Mark as Read
                        </button>
                    @endif
                </li>
            @endforeach
        </ul>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <div class="p-4 border-t">
                <button id="mark-all-read" class="w-full py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 rounded-lg">
                    Mark All as Read
                </button>
            </div>
        @endif
    </div>
</div>
                    <button
            class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-primary !rounded-button"
            >
            <div class="relative w-6 h-6 flex items-center justify-center">
                <i class="ri-message-3-line"></i>
                <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                3
                </span>
            </div>
            Messages
            </button>
           
<div class="relative inline-block text-left">

  <button id="profileBtn" class="w-12 h-12 rounded-full overflow-hidden focus:outline-none">
    <img  src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/defaultprofile.png') }}" alt="User" class="w-full h-full object-cover" />
  </button>


  <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg z-50">

    <div class="flex items-center gap-3 p-4 border-b">
      <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/defaultprofile.png') }}" alt="User" class="w-12 h-12 rounded-full object-cover" />
      <div>
        <p class="font-bold leading-tight">{{ $user->firstname }} {{ $user->lastname }}</p>
      
      </div>
    </div>

 
    <!-- Menu Items -->
<ul class="py-2 text-sm text-gray-700">
  <li>
    <a href="{{ route('customer.profile') }}" class="flex items-center px-4 py-2 hover:bg-gray-100">
      <!-- Profile Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 017 16h10a4 4 0 011.879.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
      Profile
    </a>
  </li>
  
  <li>
    <a href="{{ route('customer.appointments.view') }}" class="flex items-center px-4 py-2 hover:bg-gray-100">
      <!-- Appointment Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-13 5h16a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v7a2 2 0 002 2z" />
      </svg>
      Appointment
    </a>
  </li>

  <li>
    <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100">
      <!-- Settings Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      Settings
    </a>
  </li>
  
  <li>
    <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100">
      <!-- Help Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h4l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2z" />
      </svg>
      Help
    </a>
  </li>
  
  <li>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
      </form>
    <a href="#"onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center px-4 py-2 text-red-600 hover:bg-gray-100">
      <!-- Logout Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
      </svg>
      Logout
    </a>
  </li>
</ul>

  </div>
</div>

        </div>

      </div>
    </header>


    <script>
   document.addEventListener('DOMContentLoaded', function () {
    const notificationIcon = document.getElementById('notification-icon');
    const notificationDropdown = document.getElementById('notification-dropdown');
    const markAsReadButtons = document.querySelectorAll('.mark-as-read');
    const markAllReadButton = document.getElementById('mark-all-read');

    // Toggle Notification Dropdown
    notificationIcon.addEventListener('click', function () {
        notificationDropdown.classList.toggle('hidden');
    });

    // Mark Individual Notification as Read
    markAsReadButtons.forEach(button => {
        button.addEventListener('click', function () {
            const notificationId = this.getAttribute('data-id');
            fetch(`/notifications/mark-as-read/${notificationId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const notificationItem = this.closest('li');
                        notificationItem.classList.add('bg-gray-100');
                        notificationItem.querySelector('p').classList.replace('text-gray-600', 'text-gray-500');
                        this.remove(); // Remove the "Mark as Read" button
                        updateNotificationCount();
                    }
                });
        });
    });

    // Mark All Notifications as Read
    if (markAllReadButton) {
        markAllReadButton.addEventListener('click', function () {
            fetch(`/notifications/mark-all-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const notificationItems = document.querySelectorAll('#notification-list li');
                        notificationItems.forEach(item => {
                            item.classList.add('bg-gray-100');
                            const message = item.querySelector('p');
                            if (message) {
                                message.classList.replace('text-gray-600', 'text-gray-500');
                            }
                            const markAsReadButton = item.querySelector('.mark-as-read');
                            if (markAsReadButton) {
                                markAsReadButton.remove();
                            }
                        });
                        updateNotificationCount();
                    }
                });
        });
    }

    // Update Notification Count
    function updateNotificationCount() {
        const countElement = notificationIcon.querySelector('span');
        const currentCount = parseInt(countElement.textContent) || 0;
        if (currentCount > 1) {
            countElement.textContent = currentCount - 1;
        } else {
            countElement.remove();
        }
    }
});
    </script>