<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Freelancer Profile</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel ="stylesheet" href="{{asset ('css/PostSeeProfile.css')}}" />
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
      /* :where([class^="ri-"])::before { content: "\f3c2"; } */
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
      <a href="/" class="font-poppins text-2xl font-semibold">
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

    <main class="max-w-7xl mx-auto px-8 py-8">
    <div class="mb-4">
    <a href="{{ route('customer.dashboard') }}">
        <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-primary bg-white border border-primary hover:bg-primary hover:text-white transition rounded-lg shadow-sm">
            <i class="ri-arrow-left-line text-lg"></i>
            
        </button>
    </a>
</div>
    <div class="grid grid-cols-3 gap-8">
        <!-- Left Section -->
        <div class="col-span-2">
            <div class="bg-white rounded-lg p-8 mb-8">
                <!-- Profile Section -->
                <div class="flex gap-6 mb-6">
                    <img
                        src="{{ $freelancer->profile_picture ? asset('storage/' . $freelancer->profile_picture) : asset('images/defaultprofile.jpg') }}"
                        alt="{{ $freelancer->firstname }}"
                        class="w-40 h-40 rounded-full object-cover"
                    />
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h2 class="text-2xl font-semibold mb-1">{{ $freelancer->firstname }} {{ $freelancer->lastname }}</h2>
                                @if($freelancer->categories->isEmpty())
                                    <p class="text-gray-600">No categories selected</p>
                                @else
                                    @foreach($freelancer->categories as $category)
                                        <p class="text-gray-600">{{ $category->name }}</p>
                                    @endforeach
                                @endif
                            </div>
                            <div class="flex items-center gap-1">
                            @if($freelancer->is_verified)
                                <div class="w-5 h-5 flex items-center justify-center text-blue-500">
                                    <i class="ri-verified-badge-fill"></i>
                                </div>
                                <span class="text-sm font-medium text-blue-500">Verified</span>
                            @else
                                <span class="text-sm font-medium text-gray-400">Not Verified</span>
                            @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-6 mb-4">
                            <div class="flex items-center gap-1">
                                <div class="w-4 h-4 flex items-center justify-center text-yellow-400">
                                    <i class="ri-star-fill"></i>
                                </div>
                                <span class="font-medium">{{ $totalStars }}</span>
                                <span class="text-gray-400">({{ $totalReviews }} reviews)</span>
                            </div>
                            <div class="flex items-center gap-1 text-gray-600">
                                <div class="w-4 h-4 flex items-center justify-center">
                                    <i class="ri-map-pin-line"></i>
                                </div>
                                <span>{{ $freelancer->province }}, {{ $freelancer->city }}, {{ $freelancer->zipcode }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <button class="flex-1 py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 !rounded-button">
                                Message
                            </button>
                            <button class="flex-1 py-2 text-sm font-medium text-primary bg-blue-50 hover:bg-blue-100 !rounded-button">
                                Contact
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Stats Section -->
                <div class="grid grid-cols-4 gap-4 p-4 bg-gray-50 rounded-lg mb-6">
                    <div class="text-center">
                        <div class="text-2xl font-semibold mb-1">98%</div>
                        <p class="text-sm text-gray-600">Job Success</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-semibold mb-1">156</div>
                        <p class="text-sm text-gray-600">Projects</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-semibold mb-1">2 hrs</div>
                        <p class="text-sm text-gray-600">Avg. Response</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-semibold mb-1">5+ yrs</div>
                        <p class="text-sm text-gray-600">Experience</p>
                    </div>
                </div>
               
            <p class="text-gray-600 leading-relaxed mb-8">
              I'm a results-driven digital marketing specialist with over 5
              years of experience helping businesses grow their online presence.
              My expertise spans across SEO, social media marketing, content
              strategy, and paid advertising. I take a data-driven approach to
              create comprehensive marketing strategies that deliver measurable
              results.
            </p>
                <!-- Portfolio Section -->
                <div class="border-t pt-8">
                    <h3 class="text-lg font-semibold mb-6">Recent works</h3>
                    <div class="grid grid-cols-2 gap-6">
                        @forelse ($freelancer->posts as $post)
                            @forelse ($post->pictures as $picture)
                                <div class="bg-white rounded-lg overflow-hidden shadow-sm">
                                    <img
                                        src="{{ asset('storage/' . $picture->image_path) }}"
                                        alt="Portfolio"
                                        class="w-full h-48 object-cover"
                                    />
                                </div>
                            @empty
                                <p>No images available for this post.</p>
                            @endforelse
                        @empty
                            <p>No recent works available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <!-- Client Reviews Section -->
            <div class="bg-white rounded-lg p-8">
                <h3 class="text-lg font-semibold mb-6">Client Reviews</h3>
                <div class="space-y-6">
                    @if ($reviews->isNotEmpty())
                        @foreach ($reviews as $review)
                            <div class="pb-6 border-b">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-4">
                                        <img
                                            src="{{ $review->customer->profile_picture ?? asset('images/defaultprofile.jpg') }}"
                                            alt="Client"
                                            class="w-10 h-10 rounded-full object-cover"
                                        />
                                        <div>
                                            <h4 class="font-medium">{{ $review->customer->firstname ?? 'Unknown' }} {{ $review->customer->lastname ?? '' }}</h4>
                                            <p class="text-sm text-gray-600">{{ $review->customer->job_title ?? 'Client' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 text-yellow-400">
                                        @for ($i = 0; $i < $review->rating; $i++)
                                            <i class="ri-star-fill"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-600">{{ $review->review }}</p>
                            </div>
                        @endforeach
                    @else
                        <p>No reviews yet.</p>
                    @endif
                </div>
            </div>
        </div>
        <!-- Right Section -->
        <div class="col-span-1">
                <div class="bg-white rounded-lg p-6 shadow-lg sticky top-20">
                <h3 class="text-lg font-semibold mb-6">Book Appointment Here</h3>
                <p class="text-sm text-gray-600 mb-6">
                    Book a consultation to discuss your digital marketing needs and goals.
                </p>
                <button
                    id="bookButton"
                    class="w-full py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 !rounded-button"
                >
                    Book Appointment
                </button>
            </div>
        </div>
    </div>
    
   <!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-lg p-6 max-w-lg w-full mx-4 relative" style="max-height:100vh;">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-xl font-semibold">Book an Appointment</h3>
      <button id="closeModal" class="text-gray-400 hover:text-gray-600">
        <div class="w-6 h-6 flex items-center justify-center">
          <i class="ri-close-line"></i>
        </div>
      </button>
    </div>
    <form id="bookingForm" method="POST" action="{{ route('pay.commitment') }}" class="flex flex-col h-full">
      @csrf
      <input type="hidden" name="freelancer_id" value="{{ $freelancer->id }}">
      <input type="hidden" name="post_id" value="{{ $post->id }}">
      <input type="hidden" id="selectedDate" name="date" required>
      <input type="hidden" id="selectedTime" name="time" required>
      <input type="hidden" name="notes" id="notesInput">
      <input type="hidden" name="commitment_fee" value="{{ $commitment_fee ?? 100 }}">
      <!-- Scrollable Content -->
      <div class="flex-1 overflow-y-auto pr-2" style="max-height:60vh;">
        <!-- Calendar Section -->
        <div class="mb-4">
          <div class="flex items-center justify-between mb-4">
            <button type="button" id="prevMonthButton" class="text-gray-400 hover:text-gray-600">
              <div class="w-6 h-6 flex items-center justify-center">
                <i class="ri-arrow-left-s-line"></i>
              </div>
            </button>
            <h4 id="monthTitle" class="text-base font-medium"></h4>
            <button type="button" id="nextMonthButton" class="text-gray-400 hover:text-gray-600">
              <div class="w-6 h-6 flex items-center justify-center">
                <i class="ri-arrow-right-s-line"></i>
              </div>
            </button>
          </div>
          <div class="grid grid-cols-7 gap-1 text-center mb-2">
            <div class="text-sm text-gray-600">Sun</div>
            <div class="text-sm text-gray-600">Mon</div>
            <div class="text-sm text-gray-600">Tue</div>
            <div class="text-sm text-gray-600">Wed</div>
            <div class="text-sm text-gray-600">Thu</div>
            <div class="text-sm text-gray-600">Fri</div>
            <div class="text-sm text-gray-600">Sat</div>
          </div>
          <div id="calendarGrid" class="grid grid-cols-7 gap-1 text-center"></div>
        </div>

        <!-- Time Slots Section -->
        <div class="mb-6">
          <h5 class="text-sm font-medium mb-4">Available Time Slots</h5>
          <div class="grid grid-cols-3 gap-3"></div>
        </div>

        <!-- Notes Section -->
        <div class="mb-6">
          <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
          <textarea id="notes" name="notes" rows="3" class="w-full mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" placeholder="Add any additional details or instructions for the freelancer..."></textarea>
        </div>

        <!-- Commitment Fee Section -->
        <div class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
          <div class="flex items-center gap-2 mb-1">
            <i class="ri-information-line text-yellow-400 text-lg"></i>
            <span class="font-semibold text-yellow-700">Commitment Fee Required</span>
          </div>
          <p class="text-sm text-yellow-700">
            To book this appointment, a non-refundable commitment fee of 
            <span class="font-bold text-yellow-900">₱{{ number_format($commitment_fee ?? 100, 2) }}</span>
            is required. This fee will not be refunded if you cancel after the freelancer accepts your booking.
          </p>
        </div>
      </div>

      <!-- Sticky Submit Button -->
      <div class="pt-4 bg-white sticky bottom-0 left-0 right-0">
        <button type="submit" class="w-full py-3 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded">
        Proceed to Payment
        </button>
      </div>
    </form>
  </div>
</div>
</main>
 
 
      <!-- Success message -->
      @if(session('success'))
        <div class="alert alert-success">
        <i class='bx bx-check-circle'></i> 
        {{ session('success') }}
        </div>
        @endif
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

  // succes message time duration
  document.addEventListener('DOMContentLoaded', function () {
        const alert = document.querySelector('.alert-success');
        if (alert) {
            setTimeout(() => {
                alert.remove();
            }, 3000); // 3 seconds
        }
    });



    document.addEventListener("DOMContentLoaded", function () {
    const bookButton = document.getElementById("bookButton");
    const bookingModal = document.getElementById("bookingModal");
    const closeModal = document.getElementById("closeModal");
    const calendarGrid = document.getElementById("calendarGrid");
    const monthTitle = document.getElementById("monthTitle");
    const nextMonthButton = document.getElementById("nextMonthButton");
    const prevMonthButton = document.getElementById("prevMonthButton");
    const selectedDateInput = document.getElementById("selectedDate");
    const selectedTimeInput = document.getElementById("selectedTime");
    const timeButtonsContainer = document.querySelector(".grid-cols-3");

    const months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ];

    let currentDate = new Date();
    let freelancerId = "{{ $freelancer->id }}";

    // Function to fetch availability
    function fetchAvailability(year, month) {
    return fetch(`/freelancer/${freelancerId}/availability?year=${year}&month=${month + 1}`)
        .then(response => response.json())
        .then(data => {
            console.log("Fetched availability:", data); // Debugging
            return data;
        })
        .catch(error => {
            console.error("Error fetching availability:", error);
            return [];
        });
}

    // Function to update the calendar
    async function updateCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    monthTitle.textContent = `${months[month]} ${year}`;
    calendarGrid.innerHTML = "";

    // Fetch availability for the current month
    const availability = await fetchAvailability(year, month);

    // Add empty cells for days before the first day of the month
    for (let i = 0; i < firstDay; i++) {
        calendarGrid.innerHTML += `<div class="text-sm text-gray-300"></div>`;
    }

    // Add cells for each day of the month
    for (let day = 1; day <= lastDate; day++) {
        const date = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
        const isAvailable = availability.some(avail => avail.date === date);

        calendarGrid.innerHTML += `
            <div class="text-sm py-2 ${
                isAvailable
                    ? "bg-green-600 text-white rounded-full cursor-pointer"
                    : "bg-gray-200 text-gray-400 cursor-not-allowed"
            }" data-date="${date}" ${isAvailable ? "" : "disabled"}>
                ${day}
            </div>`;
    }

    // Add click event to available days only
    document.querySelectorAll("#calendarGrid div[data-date]").forEach((day) => {
        if (!day.classList.contains("cursor-not-allowed")) {
            day.addEventListener("click", function () {
                document
                    .querySelectorAll("#calendarGrid div")
                    .forEach((d) => d.classList.remove("bg-green-600", "text-white"));
                this.classList.add("bg-green-600", "text-white");
                selectedDateInput.value = this.getAttribute("data-date");

                // Update time slots for the selected date
                updateTimeSlots(this.getAttribute("data-date"), availability);
            });
        }
    });
}

function updateTimeSlots(selectedDate, availability) {
    const timeButtonsContainer = document.querySelector("#bookingModal .grid-cols-3"); // Target the modal's time slot container
    const availableTimes = availability.find(avail => avail.date === selectedDate);
    timeButtonsContainer.innerHTML = ""; // Clear previous time slots

    if (availableTimes) {
        const startTime = parseInt(availableTimes.start_time.split(":")[0]);
        const endTime = parseInt(availableTimes.end_time.split(":")[0]);
        const bookedTimes = availableTimes.booked_times || []; // Get booked times

        for (let hour = startTime; hour < endTime; hour++) {
            const time24 = `${hour}:00`;
            const time12 = convertTo12HourFormat(hour); // Convert to 12-hour format
            const isBooked = bookedTimes.includes(time24); // Check if the time is booked

            timeButtonsContainer.innerHTML += `
                <button
                    type="button"
                    class="time-btn text-sm py-2 border border-gray-200 rounded hover:border-gray-300 ${
                        isBooked ? "bg-gray-300 text-gray-500 cursor-not-allowed" : ""
                    }"
                    data-time="${time24}"
                    ${isBooked ? "disabled" : ""}
                >
                    ${time12} ${isBooked ? "(Booked)" : ""}
                </button>`;
        }

        // Add click event to time buttons
        document.querySelectorAll(".time-btn:not([disabled])").forEach((button) => {
            button.addEventListener("click", function () {
                document.querySelectorAll(".time-btn").forEach((btn) => btn.classList.remove("bg-green-600", "text-white"));
                this.classList.add("bg-green-600", "text-white");
                document.getElementById("selectedTime").value = this.getAttribute("data-time");
            });
        });
    } else {
        timeButtonsContainer.innerHTML = `<p class="text-sm text-gray-600">No available time slots for this date.</p>`;
    }
}

// Helper function to convert 24-hour time to 12-hour format
function convertTo12HourFormat(hour) {
    const period = hour >= 12 ? "PM" : "AM";
    const hour12 = hour % 12 || 12; // Convert 0 to 12 for midnight
    return `${hour12}:00 ${period}`;
}
    // Event listeners for navigation buttons
    nextMonthButton.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        updateCalendar();
    });

    prevMonthButton.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        updateCalendar();
    });

    // Open and close modal
    bookButton.addEventListener("click", () => {
        bookingModal.classList.remove("hidden");
        updateCalendar(); // Initialize calendar when modal opens
    });

    closeModal.addEventListener("click", () => {
        bookingModal.classList.add("hidden");
    });

    bookingModal.addEventListener("click", (e) => {
        if (e.target === bookingModal) {
            bookingModal.classList.add("hidden");
        }
    });
});


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
            },
          },
        };


        document.getElementById('bookingForm').addEventListener('submit', function(e) {
    document.getElementById('notesInput').value = document.getElementById('notes').value;
   // Validation for date and time
   const date = document.getElementById('selectedDate').value;
    const time = document.getElementById('selectedTime').value;
    if (!date || !time) {
        e.preventDefault();
        alert('Please select both a date and a time before proceeding to payment.');
        return false;
    }
  
  });
      

      </script>
  </body>
</html>
