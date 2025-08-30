<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <link rel="stylesheet" href="{{asset('css/customerProfile.css')}}">
    <link rel="stylesheet" href="{{asset('css/customerHeader.css')}}" />
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
              primary: "#2563eb", // Changed from #118f39 to royal blue
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
<body class="bg-gray-50">
    
    @include('customer.customerHeader')
    @include('successMessage')
    

    <main class="max-w-7xl mx-auto px-4 md:px-8 py-6">
        <!-- Back Button - Using consistent style with PostSeeProfile -->
        <div class="mb-4">
            <a href="{{ route('customer.dashboard') }}">
                <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-600 bg-white border border-blue-600 hover:bg-blue-600 hover:text-white transition rounded-lg shadow-sm">
                <i class="ri-arrow-left-line text-lg"></i>
                </button>
            </a>
            </div>
        
        <!-- Profile Section -->
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Profile Info Card -->
            <div class="bg-white rounded-lg shadow p-6 text-center md:w-64">
                <img src="{{ $user && $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/defaultprofile.png') }}" 
                     alt="Profile Picture" 
                     class="w-32 h-32 md:w-40 md:h-40 rounded-full mx-auto border-4 border-white shadow object-cover">
                <h2 class="mt-4 text-xl font-semibold"><span class="text-primary">Hi,</span> {{ $user->firstname }} {{ $user->lastname }}</h2>
                <button id="editProfileBtn" class="mt-4 flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 w-full">
                    <i class='bx bx-edit'></i>
                    Edit Profile
                </button>
            </div>

            <!-- Profile Details Card -->
            <div class="flex-1 bg-white rounded-lg shadow p-6">
                <div class="border-b pb-3 mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Personal Details</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                   <p class="flex items-start gap-2">
                        <i class="ri-user-line text-blue-600 mt-1"></i> <!-- Changed from text-primary -->
                        <span>
                            <strong class="block text-sm text-gray-500">First Name</strong>
                            <span class="text-gray-800">{{ $user->firstname }}</span>
                        </span>
                    </p>
                    <p class="flex items-start gap-2">
                        <i class="ri-user-line text-blue-600 mt-1"></i>
                        <span>
                            <strong class="block text-sm text-gray-500">Last Name</strong>
                            <span class="text-gray-800">{{ $user->lastname }}</span>
                        </span>
                    </p>
                    <p class="flex items-start gap-2">
                        <i class="ri-mail-line text-primary mt-1"></i>
                        <span>
                            <strong class="block text-sm text-gray-500">Email</strong>
                            <span class="text-gray-800">{{ $user->email }}</span>
                        </span>
                    </p>
                    <p class="flex items-start gap-2">
                        <i class="ri-phone-line text-primary mt-1"></i>
                        <span>
                            <strong class="block text-sm text-gray-500">Contact No.</strong>
                            <span class="text-gray-800">{{ $user->contact_number ?: 'Not provided' }}</span>
                        </span>
                    </p>
                    <p class="flex items-start gap-2 md:col-span-2">
                        <i class="ri-map-pin-line text-primary mt-1"></i>
                        <span>
                            <strong class="block text-sm text-gray-500">Address</strong>
                            <span class="text-gray-800">{{ $user->province }}, {{ $user->city }}, {{ $user->zipcode }}</span>
                        </span>
                    </p>
                </div>
                
                <div class="mt-6">
                   <p class="flex items-center gap-2 mb-3">
                        <i class="ri-share-line text-blue-600"></i> <!-- Changed from text-primary -->
                        <strong class="text-gray-700">Social Media</strong>
                    </p>
                    <div class="flex gap-4 mt-2">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-blue-600 hover:bg-blue-100 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-blue-400 hover:bg-blue-100 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-pink-500 hover:bg-pink-100 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-blue-700 hover:bg-blue-100 transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Freelancers Section -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                <i class="ri-team-line text-blue-600 mr-2"></i>Recent Freelancers <!-- Changed from text-primary -->
                Recent Freelancers
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse ($recentFreelancers as $freelancer)
                    <div class="bg-white rounded-lg shadow p-6 transition-transform hover:-translate-y-1">
                        <!-- Freelancer Profile Picture -->
                        <img 
                            src="{{ $freelancer->profile_picture ? asset('storage/' . $freelancer->profile_picture) : asset('images/defaultprofile.jpg') }}" 
                            alt="{{ $freelancer->firstname }} {{ $freelancer->lastname }}" 
                            class="w-24 h-24 rounded-full mx-auto object-cover shadow-md"
                        >
                        <!-- Freelancer Name -->
                        <h4 class="mt-4 text-center text-lg font-medium text-gray-800">
                            {{ $freelancer->firstname }} {{ $freelancer->lastname }}
                        </h4>
                        <!-- Freelancer Categories -->
                        <div class="text-center mt-2 flex flex-wrap justify-center gap-2">
                            @forelse ($freelancer->categories as $category)
                               <span class="inline-block bg-blue-100 text-blue-600 text-xs font-medium px-2 py-1 rounded-full">
                                    {{ $category->name }}
                                </span>
                            @empty
                                <span class="text-gray-400">No categories</span>
                            @endforelse
                        </div>
                        <!-- View Profile Button -->
                        <a 
                            href="{{ route('freelancer.profile', $freelancer->id) }}" 
                            class="block w-full mt-4 py-2 text-center text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition" <!-- Changed from bg-primary -->
                        >
                            <i class="ri-eye-line mr-1"></i>View Profile
                        </a>
                    </div>
                @empty
                    <div class="md:col-span-3 bg-white rounded-lg shadow p-6 text-center">
                        <p class="text-gray-500 flex items-center justify-center gap-2">
                            <i class="ri-information-line"></i>No recent freelancers available.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div id="editProfileModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" style="display: none;">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="p-6 border-b flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-user-edit"></i> Edit Profile
                    </h2>
                    <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Display Validation Errors -->
                @if ($errors->any())
                    <div class="bg-red-50 text-red-700 p-4 mb-4">
                        <ul class="list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Modal Body -->
                <div class="p-6">
                    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Picture -->
                        <div class="mb-6">
                            <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-image mr-2"></i>Profile Picture
                            </label>
                            <div class="flex items-center gap-4 mb-3">
                                <img id="currentProfilePicture" src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/defaultprofile.png') }}" 
                                     alt="Current Profile Picture" class="w-20 h-20 rounded-full object-cover">
                                <img id="newProfilePicturePreview" src="#" alt="New Profile Picture Preview" 
                                     class="w-20 h-20 rounded-full object-cover hidden">
                            </div>
                           <input type="file" name="profile_picture" id="profile_picture" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-500/10 file:text-blue-600 hover:file:bg-blue-500/20" accept="image/*"> 
                        </div>
                        
                        <!-- Two Column Layout -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- First Name -->
                            <div>
                                <label for="firstname" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-user mr-2"></i>First Name
                                </label>
                               <input type="text" name="firstname" id="firstname" value="{{ $user->firstname }}" 
                                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50" required>
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="lastname" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-user mr-2"></i>Last Name
                                </label>
                                <input type="text" name="lastname" id="lastname" value="{{ $user->lastname }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50" required>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-envelope mr-2"></i>Email
                                </label>
                                <input type="email" name="email" id="email" value="{{ $user->email }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500" readonly>
                            </div>

                            <!-- Contact Number -->
                            <div>
                                <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-phone mr-2"></i>Contact Number
                                </label>
                                <input type="text" name="contact_number" id="contact_number" value="{{ $user->contact_number }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50">
                            </div>

                            <!-- Province -->
                            <div>
                                <label for="province" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-map-marker-alt mr-2"></i>Province
                                </label>
                                <input type="text" name="province" id="province" value="{{ $user->province }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50" required>
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-city mr-2"></i>City
                                </label>
                                <input type="text" name="city" id="city" value="{{ $user->city }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50" required>
                            </div>

                            <!-- Zipcode -->
                            <div class="md:col-span-2">
                                <label for="zipcode" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-mail-bulk mr-2"></i>Zipcode
                                </label>
                                <input type="text" name="zipcode" id="zipcode" value="{{ $user->zipcode }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50" required>
                            </div>
                        </div>

                        <!-- Save Button -->
                      <button type="submit" class="w-full mt-4 flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        <i class="fas fa-save"></i>
                       <span class="btn-text">Save Changes</span>
                        <span class="btn-spinner" style="display:none;">
                            <i class="ri-loader-4-line animate-spin"></i>
                        </span>
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

   @include('customer.footer')

   <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editProfileBtn = document.getElementById('editProfileBtn');
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
                // Prevent scrolling on the body when modal is open
                document.body.style.overflow = 'hidden';
            });
            
            // Close Modal
            closeModalBtn.addEventListener('click', function () {
                editProfileModal.style.display = 'none';
                // Enable scrolling again
                document.body.style.overflow = '';
                
                // Reset the form fields to their original values
                resetFormFields();
            });
            
            // Close Modal on Outside Click
            window.addEventListener('click', function (e) {
                if (e.target === editProfileModal) {
                    editProfileModal.style.display = 'none';
                    // Enable scrolling again
                    document.body.style.overflow = '';
                    
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
                
                // Also reset the profile picture preview
                const newProfilePicturePreview = document.getElementById('newProfilePicturePreview');
                const currentProfilePicture = document.getElementById('currentProfilePicture');
                
                if (newProfilePicturePreview && currentProfilePicture) {
                    newProfilePicturePreview.classList.add('hidden');
                    currentProfilePicture.classList.remove('hidden');
                }
            }
            
            // Handle profile picture preview
            const profilePictureInput = document.getElementById('profile_picture');
            const currentProfilePicture = document.getElementById('currentProfilePicture');
            const newProfilePicturePreview = document.getElementById('newProfilePicturePreview');
            
            if (profilePictureInput) {
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
            }
            
            // Handle responsiveness
            function adjustModalForMobile() {
                if (window.innerWidth <= 768) {
                    // Adjust modal for mobile if it's open
                    if (editProfileModal.style.display === 'flex') {
                        const modalContent = editProfileModal.querySelector('.bg-white');
                        modalContent.style.maxHeight = '90vh';
                    }
                }
            }
            
            // Adjust modal on window resize
            window.addEventListener('resize', adjustModalForMobile);
            
            // Initialize
            adjustModalForMobile();
        });

function showSpinnerOnButton(button) {
const btnText = button.querySelector('.btn-text');
const btnSpinner = button.querySelector('.btn-spinner');
button.disabled = true;
button.classList.add('disabled');
if (btnText) btnText.style.display = 'none';
if (btnSpinner) btnSpinner.style.display = 'inline-block';
}
function restoreButton(button, text) {
    const btnText = button.querySelector('.btn-text');
    const btnSpinner = button.querySelector('.btn-spinner');
    button.disabled = false;
    button.classList.remove('disabled');
    if (btnText) btnText.style.display = 'inline-block';
    if (btnSpinner) btnSpinner.style.display = 'none';
    if (btnText && text) btnText.textContent = text;
}
    </script>
  
</body>
</html>