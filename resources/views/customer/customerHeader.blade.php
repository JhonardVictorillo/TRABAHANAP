<header class="sticky top-0 z-50 bg-white shadow-sm">
  <!-- Main header container with proper alignment -->
  <div class="max-w-7xl mx-auto px-4 sm:px-8">
    <div class="flex items-center justify-between h-16">
      <!-- Logo Section - Left aligned -->
      <a href="" class="font-poppins text-xl sm:text-2xl font-extrabold">
        <span class="text-[#2563eb]">Mingla</span><span class="text-[#3b82f6]">Gawa</span> 
      </a>
      
      <!-- Desktop Search Bar - Center -->
      <div class="hidden sm:flex items-center flex-1 max-w-xl mx-4 sm:mx-8">
        <div class="relative w-full">
          <form action="{{ route('search') }}" method="GET" style="display: flex; align-items: center; width: 100%;">
            <input
              type="text"
              name="q" 
              class="search-bar w-full h-10 pl-10 pr-4 text-sm bg-gray-50 border-none !rounded-button"
              placeholder="Search for services or freelancers..."
              value="{{ request('q') }}"
            />
            <div class="absolute left-3 top-0 w-4 h-10 flex items-center justify-center text-gray-400">
              <i class="ri-search-line"></i>
            </div>
          </form>
        </div>
      </div>
      
      <!-- Right Section - Notifications and Profile -->
      <div class="flex items-center gap-2 sm:gap-6">
        <!-- Notifications -->
        <div class="relative inline-block text-left" id="notification-container">
          <!-- Notification Icon - Mobile-friendly -->
          <button id="notification-icon" class="flex items-center gap-1 sm:gap-2 text-sm font-medium text-gray-700 hover:text-primary">
            <div class="relative w-6 h-6 flex items-center justify-center">
              <i class="ri-notification-line"></i>
              @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                  {{ auth()->user()->unreadNotifications->count() }}
                </span>
              @endif
            </div>
            <span class="hidden sm:inline">Notifications</span>
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
        
        <!-- Profile Dropdown -->
        <div class="relative inline-block text-left">
          <button id="profileBtn" class="w-12 h-12 rounded-full overflow-hidden focus:outline-none">
            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/defaultprofile.png') }}" alt="User" class="w-full h-full object-cover" />
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
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center px-4 py-2 text-red-600 hover:bg-gray-100">
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
  </div>

  <!-- Mobile search bar -->
  <div class="sm:hidden px-4 py-2 border-t border-gray-100">
    <form action="{{ route('search') }}" method="GET" class="w-full">
      <div class="relative w-full">
        <input
          type="text"
          name="q" 
          class="search-bar w-full h-10 pl-10 pr-4 text-sm bg-gray-50 border-none !rounded-button"
          placeholder="Search..."
          value="{{ request('q') }}"
        />
        <div class="absolute left-3 top-0 w-4 h-10 flex items-center justify-center text-gray-400">
          <i class="ri-search-line"></i>
        </div>
      </div>
    </form>
  </div>
</header>

<script>

const profileBtn = document.getElementById('profileBtn');
          const dropdownMenu = document.getElementById('dropdownMenu');

          profileBtn.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
          });

          document.addEventListener('click', (e) => {
            if (!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
              dropdownMenu.classList.add('hidden');
            }
          });
       



    document.addEventListener('DOMContentLoaded', function () {
    const notificationIcon = document.getElementById('notification-icon');
    const notificationDropdown = document.getElementById('notification-dropdown');
    const markAsReadButtons = document.querySelectorAll('.mark-as-read');
    const markAllReadButton = document.getElementById('mark-all-read');

    // Toggle Notification Dropdown with improved mobile handling
    if (notificationIcon && notificationDropdown) {
        notificationIcon.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent event bubbling
            
            // Toggle body class for overlay effect
            document.body.classList.toggle('notification-open');
            
            // Close profile dropdown if open
            const dropdownMenu = document.getElementById('dropdownMenu');
            if (dropdownMenu && !dropdownMenu.classList.contains('hidden')) {
                dropdownMenu.classList.add('hidden');
            }
            
            // Toggle visibility with proper positioning
            if (notificationDropdown.classList.contains('hidden')) {
                // Position dropdown properly before showing
                if (window.innerWidth <= 768) {
                    // Calculate position based on header height
                    const headerHeight = document.querySelector('header').offsetHeight;
                    notificationDropdown.style.top = headerHeight + 'px';
                }
                
                notificationDropdown.classList.remove('hidden');
            } else {
                notificationDropdown.classList.add('hidden');
                document.body.classList.remove('notification-open');
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (notificationDropdown && !notificationIcon.contains(e.target) && !notificationDropdown.contains(e.target)) {
                notificationDropdown.classList.add('hidden');
                document.body.classList.remove('notification-open');
            }
        });
    }

    // Mark Individual Notification as Read
    if (markAsReadButtons && markAsReadButtons.length > 0) {
        markAsReadButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.stopPropagation(); // Prevent dropdown from closing
                const notificationId = this.getAttribute('data-id');
                
                if (!notificationId) return;
                
                fetch(`/notifications/mark-as-read/${notificationId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const notificationItem = this.closest('li');
                        if (notificationItem) {
                            notificationItem.classList.add('bg-gray-100');
                            const message = notificationItem.querySelector('p');
                            if (message) {
                                message.classList.replace('text-gray-600', 'text-gray-500');
                            }
                            this.remove(); // Remove the "Mark as Read" button
                            updateNotificationCount();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                });
            });
        });
    }

    // Mark All Notifications as Read
    if (markAllReadButton) {
        markAllReadButton.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent dropdown from closing
            
            fetch(`/notifications/mark-all-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
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
                  const countElement = notificationIcon.querySelector('span');
                if (countElement) {
                    countElement.remove();
                }
                    
                    // Hide mark all read button after using it
                    const buttonParent = this.closest('.p-4');
                if (buttonParent) {
                    buttonParent.classList.add('hidden');
                }
            }
        })
            .catch(error => {
                console.error('Error marking all notifications as read:', error);
            });
        });
    }

    // Update Notification Count
    function updateNotificationCount() {
        const countElement = notificationIcon.querySelector('span');
        if (!countElement) return;
        
        const currentCount = parseInt(countElement.textContent) || 0;
        if (currentCount > 1) {
            countElement.textContent = currentCount - 1;
        } else {
            countElement.remove();
        }
    }
    
    // Ensure proper sizing on window resize
    window.addEventListener('resize', function() {
        if (!notificationDropdown.classList.contains('hidden') && window.innerWidth <= 768) {
            const headerHeight = document.querySelector('header').offsetHeight;
            notificationDropdown.style.top = headerHeight + 'px';
        }
    });
});
</script>