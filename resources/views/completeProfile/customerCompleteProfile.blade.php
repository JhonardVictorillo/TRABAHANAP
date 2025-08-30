<!-- First Modal: Ask the user to complete their profile -->
<div class="cc-modal" id="completeAccountModal">
    <div class="cc-modal-content">
        <div class="cc-welcome-header">
            <div class="cc-welcome-icon">
                <i class='bx bx-user-circle'></i>
            </div>
            <h2>Welcome to MinglaGawa</h2>
            <p>Complete your profile to get the most out of our services and connect with skilled freelancers.</p>
        </div>
        <button id="completeAccountBtn" type="button" class="cc-complete-btn">Complete Your Profile</button>
    </div>
</div>

<!-- Main Profile Form Modal -->
<div class="cc-modal" id="completeProfileModal" style="display: {{ $errors->any() ? 'flex': 'none'}}">
    <div class="cc-modal-content">
        <!-- Form header -->
        <div class="cc-form-header">
            <h2>Complete Your Customer Profile</h2>
            <p>Tell us more about yourself to help us personalize your experience</p>
        </div>
        
        <form action="{{ route('profile.complete') }}" method="POST" enctype="multipart/form-data" id="customerProfileForm">
            @csrf
            <div class="cc-form-container">
                <!-- Personal Details -->
                <div class="cc-form-section">
                    <h3 class="cc-section-title"><i class='bx bx-user'></i> Personal Details</h3>
                    
                    <!-- Profile Picture Upload -->
                    <div class="cc-profile-upload">
                        <div class="cc-profile-preview">
                            <img id="profileImagePreview" src="{{ asset('images/defaultprofile-placeholder.png') }}" alt="Profile Preview">
                            <div class="cc-upload-overlay">
                                <i class='bx bx-camera'></i>
                                <span>Upload Photo</span>
                            </div>
                        </div>
                        <input type="file" name="profile_picture" id="profilePictureInput" accept="image/*" class="cc-hidden-input">
                        @error('profile_picture')
                            <div class="cc-error-message text-center">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="cc-form-row">
                        <div class="cc-input-group">
                            <label for="firstname">First Name</label>
                            <input type="text" name="firstname" id="firstname" placeholder="Your first name" value="{{ old('firstname', $user->firstname ?? '') }}" required>
                            @error('firstname')
                                <div class="cc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="cc-input-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" name="lastname" id="lastname" placeholder="Your last name" value="{{ old('lastname', $user->lastname ?? '') }}" required>
                            @error('lastname')
                                <div class="cc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="cc-form-row">
                        <div class="cc-input-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="Your email address" value="{{ old('email', $user->email ?? '') }}" required>
                            @error('email')
                                <div class="cc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="cc-input-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number" placeholder="Your phone number" value="{{ old('contact_number', $user->contact_number ?? '') }}" required>
                            @error('contact_number')
                                <div class="cc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Address Information -->
                <div class="cc-form-section">
                    <h3 class="cc-section-title"><i class='bx bx-map'></i> Address Information</h3>
                    
                    <div class="cc-form-row">
                        <div class="cc-input-group">
                            <label for="province">Province</label>
                            <input type="text" name="province" id="province" placeholder="Your province" value="{{ old('province', $user->province ?? '') }}" required>
                            @error('province')
                                <div class="cc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="cc-input-group">
                            <label for="city">City</label>
                            <input type="text" name="city" id="city" placeholder="Your city" value="{{ old('city', $user->city ?? '') }}" required>
                            @error('city')
                                <div class="cc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="cc-form-row">
                        <div class="cc-input-group">
                            <label for="zipcode">Zipcode</label>
                            <input type="text" name="zipcode" id="zipcode" placeholder="Your zipcode" value="{{ old('zipcode', $user->zipcode ?? '') }}" required>
                            @error('zipcode')
                                <div class="cc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                       <div class="cc-input-group">
                            <label for="barangay">Barangay</label>
                            <input type="text" name="barangay" id="barangay" placeholder="Your barangay" value="{{ old('barangay', $user->barangay ?? '') }}">
                            @error('barangay')
                                <div class="cc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    
                </div>
                
                <button type="submit" class="cc-submit-btn"><i class='bx bx-check'></i> Complete Profile</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(!auth()->user()->is_profile_complete)
            document.getElementById('completeAccountModal').style.display = 'flex';
            
            // Show the profile form when button is clicked
            document.getElementById("completeAccountBtn").addEventListener("click", function() {
                document.getElementById("completeAccountModal").style.display = 'none';
                document.getElementById("completeProfileModal").style.display = 'flex';
            });
            
            // Profile picture preview
            const profileInput = document.getElementById('profilePictureInput');
            const profilePreview = document.getElementById('profileImagePreview');
            const profileContainer = document.querySelector('.cc-profile-preview');
            
            if (profileContainer) {
                profileContainer.addEventListener('click', function() {
                    profileInput.click();
                });
            }
            
            if (profileInput) {
                profileInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            profilePreview.src = e.target.result;
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }
            
        @endif   
    });


    document.getElementById('customerProfileForm').addEventListener('submit', function(e) {
    let errors = [];
    // Personal Details
    if (!document.getElementById('firstname').value.trim()) errors.push('First name is required.');
    if (!document.getElementById('lastname').value.trim()) errors.push('Last name is required.');
    if (!document.getElementById('email').value.trim()) errors.push('Email is required.');
    if (!document.getElementById('contact_number').value.trim()) errors.push('Contact number is required.');
    if (!document.getElementById('profilePictureInput').files.length) errors.push('Profile picture is required.');
    // Address
    if (!document.getElementById('province').value.trim()) errors.push('Province is required.');
    if (!document.getElementById('city').value.trim()) errors.push('City is required.');
    if (!document.getElementById('zipcode').value.trim()) errors.push('Zipcode is required.');

    // Show errors if any
    let errorBox = document.getElementById('customerProfileFormErrors');
    if (!errorBox) {
        errorBox = document.createElement('div');
        errorBox.id = 'customerProfileFormErrors';
        errorBox.className = 'cc-error-message mb-2 text-red-500';
        this.prepend(errorBox);
    }
    errorBox.innerHTML = '';
    if (errors.length) {
        errors.forEach(msg => {
            const p = document.createElement('p');
            p.textContent = msg;
            errorBox.appendChild(p);
        });
        e.preventDefault();
        window.scrollTo(0, 0);
    }
});
</script>