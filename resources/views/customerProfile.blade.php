<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <link rel="stylesheet" href="{{('css/customerProfile.css')}}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
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
</head>
<body>
    

<header class="sticky top-0 z-50 bg-white shadow-sm">
      <div class="flex items-center justify-between px-8 h-16">
      <a href="/" class="font-poppins text-2xl font-extrabold">
          <span class="text-[#118f39]">Mingla</span><span class="text-[#4CAF50]">Gawa</span> 
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

    <main class="main-content">
    <a href="{{ route('customer.dashboard') }}">
    <button class="back-btn">Back</button>
</a>
        <div class="profile-section">
            <div class="profile-info">
                <img  src="{{ $user && $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/defaultprofile.png') }}"  alt="Profile Picture" class="main-profile-picture">
                <h2> <span class="hi">Hi,</span> {{ $user->firstname }} {{ $user->lastname }}</h2>
                <a href="#"  id="editProfileBtn" class="edit-profile"><i class='bx bx-edit'></i>Edit Profile</a>
            </div>

            <div class="profile">
                <div class="profile-header">
                    <h3><span>Personal Details</span></h3>
                </div>
                <div class="profile-details">
                    <!-- <div class="service-category">
                        <h4>Service Category</h4>
                        <span>Technician</span>
                        <span>Food & Pastries</span>
                        <span>Arts & Media</span>
                        <span>Party or Events</span>
                    </div> -->
                    <div class="personal-details">
                    <p><i class="ri-user-line text-primary mr-2"></i><strong>First Name:</strong> <span>{{ $user->firstname }}</span></p>
                    <p><i class="ri-user-line text-primary mr-2"></i><strong>Last Name:</strong> <span>{{ $user->lastname }}</span></p>
                    <p><i class="ri-mail-line text-primary mr-2"></i><strong>Email:</strong> <span>{{ $user->email }}</span></p>
                    <p><i class="ri-phone-line text-primary mr-2"></i><strong>Contact No.:</strong> <span>{{ $user->contact_number }}</span></p>
                    <p><i class="ri-map-pin-line text-primary mr-2"></i><strong>Address:</strong> <span>{{ $user->province }}, {{ $user->city }}, {{ $user->zipcode }}</span></p>
                        
                    <div class="social-media mt-4">
                            <p><i class="ri-share-line text-primary mr-2"></i><strong>Social Media:</strong></p>
                            <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-blue-400 hover:text-blue-600"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-pink-500 hover:text-pink-700"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-blue-700 hover:text-blue-900"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="recent-freelancers mt-8">
    <h3 class="text-lg font-semibold text-gray-700 mb-4"><i class="ri-team-line text-primary mr-2"></i>Recent Freelancers</h3>
    <div class="freelancer-gallery grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse ($recentFreelancers as $freelancer)
            <div class="freelancer-card bg-white rounded-lg shadow-lg p-8">
                <!-- Freelancer Profile Picture -->
                <img 
                    src="{{ $freelancer->profile_picture ? asset('storage/' . $freelancer->profile_picture) : asset('images/defaultprofile.jpg') }}" 
                    alt="{{ $freelancer->firstname }} {{ $freelancer->lastname }}" 
                    class="w-24 h-24 rounded-full mx-auto object-cover shadow-md"
                >
                <!-- Freelancer Name -->
                <h4 class="mt-4 text-center text-lg font-medium text-gray-800">
                    <i class="ri-user-line text-primary mr-2"></i>{{ $freelancer->firstname }} {{ $freelancer->lastname }}
                </h4>
                <!-- Freelancer Categories -->
                <p class="text-center text-sm text-gray-500 mt-2">
                    @forelse ($freelancer->categories as $category)
                        <span class="inline-block bg-blue-100 text-blue-600 text-xs font-medium px-2 py-1 rounded-full">
                            <i class="ri-folder-line mr-1"></i>{{ $category->name }}
                        </span>
                    @empty
                        <span class="text-gray-400">No categories</span>
                    @endforelse
                </p>
                <!-- View Profile Button -->
                <a 
                    href="{{ route('freelancer.profile', $freelancer->id) }}" 
                    class="block mt-4 text-center text-sm font-medium text-primary hover:underline"
                >
                    <i class="ri-eye-line mr-1"></i>View Profile
                </a>
            </div>
        @empty
            <p class="text-gray-500"><i class="ri-information-line mr-2"></i>No recent freelancers available.</p>
        @endforelse
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="modal hidden">
    <div class="modal-content">
        <!-- Close Button -->
        <span id="closeModalBtn" class="close">&times;</span>
        <h2 class="modal-title"><i class="fas fa-user-edit"></i> Edit Profile</h2>
              <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <!-- Profile Picture Preview -->
                <div class="form-group full-width">
                    <label for="profile_picture"><i class="fas fa-image"></i> Profile Picture</label>
                    <div class="image-preview-container">
                        <!-- Current Profile Picture -->
                        <img id="currentProfilePicture" src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/defaultprofile.png') }}" alt="Current Profile Picture" class="image-preview">
                        <!-- New Profile Picture Preview -->
                        <img id="newProfilePicturePreview" src="#" alt="New Profile Picture Preview" class="image-preview hidden">
                    </div>
                    <input type="file" name="profile_picture" id="profile_picture" class="input-field" accept="image/*">
                </div>

                <!-- First Name -->
                <div class="form-group">
                    <label for="firstname"><i class="fas fa-user"></i> First Name</label>
                    <input type="text" name="firstname" id="firstname" value="{{ $user->firstname }}" class="input-field" required>
                </div>

                <!-- Last Name -->
                <div class="form-group">
                    <label for="lastname"><i class="fas fa-user"></i> Last Name</label>
                    <input type="text" name="lastname" id="lastname" value="{{ $user->lastname }}" class="input-field" required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" id="email" value="{{ $user->email }}" class="input-field" readonly required>
                </div>

                <!-- Contact Number -->
                <div class="form-group">
                    <label for="contact_number"><i class="fas fa-phone"></i> Contact Number</label>
                    <input type="text" name="contact_number" id="contact_number" value="{{ $user->contact_number }}" class="input-field">
                </div>

                <!-- Province -->
                <div class="form-group">
                    <label for="province"><i class="fas fa-map-marker-alt"></i> Province</label>
                    <input type="text" name="province" id="province" value="{{ $user->province }}" class="input-field" required>
                </div>

                <!-- City -->
                <div class="form-group">
                    <label for="city"><i class="fas fa-city"></i> City</label>
                    <input type="text" name="city" id="city" value="{{ $user->city }}" class="input-field" required>
                </div>

                <!-- Zipcode -->
                <div class="form-group">
                    <label for="zipcode"><i class="fas fa-mail-bulk"></i> Zipcode</label>
                    <input type="text" name="zipcode" id="zipcode" value="{{ $user->zipcode }}" class="input-field" required>
                </div>
            </div>

            <!-- Save Button -->
            <div class="form-group full-width">
                <button type="submit" class="save-btn"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>
    </main>

    <footer class="bg-gray-50 pt-16 pb-8">
      <div class="max-w-7xl mx-auto px-8">
        <div class="grid grid-cols-4 gap-8 pb-12 border-b">
          <div>
            <a
              href="/"
              class="font-['Pacifico'] text-2xl text-primary block mb-4"
              >MinglaGawa</a
            >
            <p class="text-sm text-gray-600 mb-6">
              Your trusted platform for finding and hiring local freelance
              talent.
            </p>
            <div class="flex gap-4">
              <a
                href="#"
                class="w-8 h-8 flex items-center justify-center text-gray-600 hover:text-primary"
              >
                <i class="ri-twitter-x-line"></i>
              </a>
              <a
                href="#"
                class="w-8 h-8 flex items-center justify-center text-gray-600 hover:text-primary"
              >
                <i class="ri-facebook-circle-line"></i>
              </a>
              <a
                href="#"
                class="w-8 h-8 flex items-center justify-center text-gray-600 hover:text-primary"
              >
                <i class="ri-instagram-line"></i>
              </a>
              <a
                href="#"
                class="w-8 h-8 flex items-center justify-center text-gray-600 hover:text-primary"
              >
                <i class="ri-linkedin-box-line"></i>
              </a>
            </div>
          </div>
          <div>
            <h4 class="font-medium mb-4">Company</h4>
            <ul class="space-y-3 text-sm">
              <li>
                <a href="#" class="text-gray-600 hover:text-primary"
                  >About Us</a
                >
              </li>
              <li>
                <a href="#" class="text-gray-600 hover:text-primary">Careers</a>
              </li>
              <li>
                <a href="#" class="text-gray-600 hover:text-primary"
                  >Press & News</a
                >
              </li>
              <li>
                <a href="#" class="text-gray-600 hover:text-primary"
                  >Partnerships</a
                >
              </li>
            </ul>
          </div>
          <div>
            <h4 class="font-medium mb-4">Support</h4>
            <ul class="space-y-3 text-sm">
              <li>
                <a href="#" class="text-gray-600 hover:text-primary"
                  >Help & Support</a
                >
              </li>
              <li>
                <a href="#" class="text-gray-600 hover:text-primary"
                  >Trust & Safety</a
                >
              </li>
              <li>
                <a href="#" class="text-gray-600 hover:text-primary"
                  >Contact Us</a
                >
              </li>
              <li>
                <a href="#" class="text-gray-600 hover:text-primary">FAQ</a>
              </li>
            </ul>
          </div>
          <div>
            <h4 class="font-medium mb-4">Legal</h4>
            <ul class="space-y-3 text-sm">
              <li>
                <a href="#" class="text-gray-600 hover:text-primary"
                  >Privacy Policy</a
                >
              </li>
              <li>
                <a href="#" class="text-gray-600 hover:text-primary"
                  >Terms of Service</a
                >
              </li>
              <li>
                <a href="#" class="text-gray-600 hover:text-primary"
                  >Cookie Policy</a
                >
              </li>
              <li>
                <a href="#" class="text-gray-600 hover:text-primary"
                  >Accessibility</a
                >
              </li>
            </ul>
          </div>
        </div>
        <div class="pt-8 text-sm text-center text-gray-600">
       
        </div>
      </div>
    </footer>

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

document.addEventListener('DOMContentLoaded', function () {
    const editProfileBtn = document.querySelector('.edit-profile');
    const editProfileModal = document.getElementById('editProfileModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const modalForm = editProfileModal.querySelector('form');

    // Store original values of the form fields
    let originalValues = {};

    // Open Modal
    editProfileBtn.addEventListener('click', function (e) {
        e.preventDefault();

        // Store the original values when the modal is opened for the first time
        if (Object.keys(originalValues).length === 0) {
            const formFields = modalForm.querySelectorAll('input, textarea');
            formFields.forEach(field => {
                originalValues[field.name] = field.value;
            });
        }

        editProfileModal.style.display = 'flex';
    });

    // Close Modal
    closeModalBtn.addEventListener('click', function () {
        editProfileModal.style.display = 'none';

        // Reset the form fields to their original values
        resetFormFields();
    });

    // Close Modal on Outside Click
    window.addEventListener('click', function (e) {
        if (e.target === editProfileModal) {
            editProfileModal.style.display = 'none';

            // Reset the form fields to their original values
            resetFormFields();
        }
    });

    // Function to reset form fields to their original values
    function resetFormFields() {
        const formFields = modalForm.querySelectorAll('input, textarea');
        formFields.forEach(field => {
            if (originalValues[field.name] !== undefined) {
                field.value = originalValues[field.name];
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const profilePictureInput = document.getElementById('profile_picture');
    const currentProfilePicture = document.getElementById('currentProfilePicture');
    const newProfilePicturePreview = document.getElementById('newProfilePicturePreview');

    profilePictureInput.addEventListener('change', function (event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                newProfilePicturePreview.src = e.target.result;
                newProfilePicturePreview.classList.remove('hidden');
                currentProfilePicture.classList.add('hidden');
            };

            reader.readAsDataURL(file);
        } else {
            // If no file is selected, reset the preview
            newProfilePicturePreview.src = '#';
            newProfilePicturePreview.classList.add('hidden');
            currentProfilePicture.classList.remove('hidden');
        }
    });
});
    </script>
</body>
</html>