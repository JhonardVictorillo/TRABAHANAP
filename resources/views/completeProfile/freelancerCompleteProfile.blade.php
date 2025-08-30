<div class="fc-modal" id="completeAccountModal">
    <div class="fc-modal-content">
        <div class="fc-welcome-header">
            <div class="fc-welcome-icon">
                <i class='bx bx-user-circle'></i>
            </div>
            <h2>Welcome to MinglaGawa</h2>
            <p>You're just one step away from offering your services and connecting with clients!</p>
        </div>
        <button id="completeAccountBtn" type="button" class="fc-complete-btn">Complete Your Profile</button>
    </div>
</div>

<!-- Main Profile Form Modal -->

<div class="fc-modal" id="completeProfileModal" style="display: {{ $errors->any() ? 'flex': 'none'}}">
    <div class="fc-modal-content">
        <!-- Progress indicator -->
        <div class="fc-progress-container">
            <div class="fc-progress-bar" id="progressBar"></div>
        </div>
        
        <!-- Form header -->
        <div class="fc-form-header">
            <h2>Complete Your Freelancer Profile</h2>
            <p>Tell us more about yourself and your services</p>
        </div>
        
       <form action="{{ route('profile.complete') }}" method="POST" enctype="multipart/form-data" id="profileForm" novalidate>
            @csrf
            <div class="fc-form-container">
                <!-- Step 1: Personal Details -->
                <div class="fc-form-step" id="step1">
                    <h3><i class='bx bx-user'></i> Personal Information</h3>
                    
                    <!-- Profile picture upload with preview -->
                    <div class="fc-profile-upload-container">
                        <div class="fc-profile-preview">
                            <img id="profileImagePreview" src="{{ asset('images/defaultprofile-placeholder.png') }}" alt="Profile Preview">
                            <div class="fc-upload-overlay">
                                <i class='bx bx-camera'></i>
                                <span>Upload Photo</span>
                            </div>
                        </div>
                        <input type="file" name="profile_picture" id="profilePictureInput" accept="image/*" class="fc-hidden-file-input" required>
                        @error('profile_picture')
                            <div class="fc-error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="fc-form-row">
                        <div class="fc-input-group">
                            <label for="firstname">First Name</label>
                            <input type="text" name="firstname" id="firstname" placeholder="Your first name" value="{{ old('firstname', auth()->user()->firstname ?? '') }}" required>
                            @error('firstname')
                                <div class="fc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="fc-input-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" name="lastname" id="lastname" placeholder="Your last name" value="{{ old('lastname', auth()->user()->lastname ?? '') }}" required>
                            @error('lastname')
                                <div class="fc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="fc-form-row">
                        <div class="fc-input-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="Your email address" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                            @error('email')
                                <div class="fc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="fc-input-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number" placeholder="Your phone number" value="{{ old('contact_number', auth()->user()->contact_number ?? '') }}" required>
                            @error('contact_number')
                                <div class="fc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="fc-form-row">
                        <div class="fc-input-group fc-full-width">
                            <label for="bio">Bio</label>
                            <textarea name="bio" id="bio" rows="4" placeholder="Tell clients about yourself, your skills, and experience">{{ old('bio', auth()->user()->bio ?? '') }}</textarea>
                            <div class="fc-character-counter">
                                <span id="bioCharCount">0</span>/500 characters
                            </div>
                        </div>
                    </div>
                    
                    <div class="fc-step-buttons">
                        <button type="button" class="fc-next-btn" onclick="nextStep(1)">Next <i class='bx bx-right-arrow-alt'></i></button>
                    </div>
                </div>
                
                <!-- Step 2: Address -->
                <div class="fc-form-step" id="step2" style="display:none">
                    <h3><i class='bx bx-map'></i> Address Information</h3>
                    
                    <div class="fc-form-row">
                        <div class="fc-input-group">
                            <label for="province">Province</label>
                            <input type="text" name="province" id="province" placeholder="Your province" value="{{ old('province', auth()->user()->province ?? '') }}" required>
                            @error('province')
                                <div class="fc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="fc-input-group">
                            <label for="city">City</label>
                            <input type="text" name="city" id="city" placeholder="Your city" value="{{ old('city', auth()->user()->city ?? '') }}" required>
                            @error('city')
                                <div class="fc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="fc-form-row">
                        <div class="fc-input-group">
                            <label for="zipcode">Zipcode</label>
                            <input type="text" name="zipcode" id="zipcode" placeholder="Your zipcode" value="{{ old('zipcode', auth()->user()->zipcode ?? '') }}" required>
                            @error('zipcode')
                                <div class="fc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="fc-input-group">
                            <label for="barangay">Barangay</label>
                             <input type="text" id="barangay" name="barangay" class="fc-input" value="{{ old('barangay', auth()->user()->barangay ?? '') }}" placeholder="Your barangay">
                        </div>
                    </div>
                    
                    
                    
                    <div class="fc-step-buttons">
                        <button type="button" class="fc-prev-btn" onclick="prevStep(2)"><i class='bx bx-left-arrow-alt'></i> Back</button>
                        <button type="button" class="fc-next-btn" onclick="nextStep(2)">Next <i class='bx bx-right-arrow-alt'></i></button>
                    </div>
                </div>
                
                <!-- Step 3: ID Verification -->
                <div class="fc-form-step" id="step3" style="display:none">
                    <h3><i class='bx bx-id-card'></i> ID Verification</h3>
                    <p class="fc-step-description">Please upload a valid government-issued ID for verification purposes. Your ID helps us maintain a trusted community.</p>
                    
                    <div class="fc-id-upload-container">
                        <div class="fc-id-preview-card">
                            <div class="fc-id-preview-header">
                                <i class='bx bx-credit-card-front'></i> ID Front
                            </div>
                            <div class="fc-id-preview-image">
                                <img id="idFrontPreview" src="{{'images/id-front-placeholder.png'}}" alt="ID Front Preview">
                            </div>
                            <div class="fc-id-upload-button">
                                <label for="id_front" class="fc-upload-label">
                                    <i class='bx bx-upload'></i> Upload Front
                                </label>
                                <input type="file" name="valid_id_front" id="id_front" accept="image/*" class="fc-hidden-file-input" required>
                            </div>
                            @error('valid_id_front')
                                <div class="fc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="fc-id-preview-card">
                            <div class="fc-id-preview-header">
                                <i class='bx bx-credit-card-back'></i> ID Back
                            </div>
                            <div class="fc-id-preview-image">
                                <img id="idBackPreview" src="{{ asset('images/id-back-placeholder.png') }}" alt="ID Back Preview">
                            </div>
                            <div class="fc-id-upload-button">
                                <label for="id_back" class="fc-upload-label">
                                    <i class='bx bx-upload'></i> Upload Back
                                </label>
                                <input type="file" name="valid_id_back" id="id_back" accept="image/*" class="fc-hidden-file-input" required>
                            </div>
                            @error('valid_id_back')
                                <div class="fc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="fc-step-buttons">
                        <button type="button" class="fc-prev-btn" onclick="prevStep(3)"><i class='bx bx-left-arrow-alt'></i> Back</button>
                        <button type="button" class="fc-next-btn" onclick="nextStep(3)">Next <i class='bx bx-right-arrow-alt'></i></button>
                    </div>
                </div>
                
                <!-- Step 4: Work Categories -->
              <div class="fc-form-step" id="step4" style="display:none">
                    <h3><i class='bx bx-category'></i> Work Category</h3>
                    <p class="fc-step-description">Select the primary category that best matches your skills and services.</p>
                    
                    <div class="fc-work-category-simple">
                        @foreach($categories ?? [] as $category)
                            <div class="fc-category-radio-container">
                                <input type="radio" name="category" id="cat-{{ $category->id }}" value="{{ $category->id }}" class="fc-category-radio">
                                <label for="cat-{{ $category->id }}" class="fc-category-label">{{ $category->name }}</label>
                            </div>
                        @endforeach
                        @error('category')
                            <div class="fc-error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="fc-step-buttons">
                        <button type="button" class="fc-prev-btn" onclick="prevStep(4)"><i class='bx bx-left-arrow-alt'></i> Back</button>
                        <button type="submit" class="fc-submit-btn"><i class='bx bx-check'></i> Complete Profile</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    // Profile completion modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        // First check if elements exist before trying to add event listeners
        const completeAccountModal = document.getElementById('completeAccountModal');
        const completeAccountBtn = document.getElementById('completeAccountBtn');
        
        if (completeAccountModal && completeAccountBtn) {
            @if(!auth()->user()->is_profile_complete)
                completeAccountModal.style.display = 'flex';
                
                completeAccountBtn.addEventListener("click", function() {
                    completeAccountModal.style.display = 'none';
                    document.getElementById("completeProfileModal").style.display = 'flex';
                });
                
                // Step navigation functions
                let currentStep = 1;
                const totalSteps = 4;
                
                window.updateProgressBar = function() {
                    const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
                    document.getElementById('progressBar').style.width = `${progressPercentage}%`;
                }
                
                window.nextStep = function(step) {
                    // Validate current step before proceeding
                    if (!validateStep(step)) return;
                    
                    document.getElementById(`step${step}`).style.display = 'none';
                    document.getElementById(`step${step+1}`).style.display = 'block';
                    currentStep = step + 1;
                    updateProgressBar();
                    window.scrollTo(0, 0);
                }
                
                window.prevStep = function(step) {
                    document.getElementById(`step${step}`).style.display = 'none';
                    document.getElementById(`step${step-1}`).style.display = 'block';
                    currentStep = step - 1;
                    updateProgressBar();
                    window.scrollTo(0, 0);
                }
                
                function validateStep(step) {
                    // Basic validation logic for each step
                    if (step === 1) {
                        const firstname = document.getElementById('firstname').value;
                        const lastname = document.getElementById('lastname').value;
                        const email = document.getElementById('email').value;
                        const contact = document.getElementById('contact_number').value;
                        const profilePic = document.getElementById('profilePictureInput').files.length;
                        
                        if (!firstname || !lastname || !email || !contact) {
                            alert('Please fill in all required fields before proceeding.');
                            return false;
                        }
                        
                        if (!profilePic) {
                            alert('Please upload a profile picture before proceeding.');
                            return false;
                        }
                        
                        return true;
                    }
                    
                    if (step === 2) {
                        const province = document.getElementById('province').value;
                        const city = document.getElementById('city').value;
                        const zipcode = document.getElementById('zipcode').value;
                        
                        if (!province || !city || !zipcode) {
                            alert('Please fill in all required address fields before proceeding.');
                            return false;
                        }
                        return true;
                    }
                    
                    if (step === 3) {
                        const idFront = document.getElementById('id_front').files.length;
                        const idBack = document.getElementById('id_back').files.length;
                        
                        if (!idFront || !idBack) {
                            alert('Please upload both front and back sides of your ID before proceeding.');
                            return false;
                        }
                        return true;
                    }
                    
                    // Add this for step 4
                    if (step === 4) {
                        const selectedCategory = document.querySelector('input[name="category"]:checked');
                        if (!selectedCategory) {
                            alert('Please select a category before completing your profile.');
                            return false;
                        }
                        return true;
                    }
                    
                    return true;
                }
                
                // Initialize previews and event handlers
                
                // Profile picture preview
                const profileInput = document.getElementById('profilePictureInput');
                const profilePreview = document.getElementById('profileImagePreview');
                const profileContainer = document.querySelector('.fc-profile-preview');
                
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
                
                // ID Front preview
                const idFrontInput = document.getElementById('id_front');
                const idFrontPreview = document.getElementById('idFrontPreview');
                
                if (idFrontInput) {
                    idFrontInput.addEventListener('change', function() {
                        if (this.files && this.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                idFrontPreview.src = e.target.result;
                            }
                            reader.readAsDataURL(this.files[0]);
                        }
                    });
                }
                
                // ID Back preview
                const idBackInput = document.getElementById('id_back');
                const idBackPreview = document.getElementById('idBackPreview');
                
                if (idBackInput) {
                    idBackInput.addEventListener('change', function() {
                        if (this.files && this.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                idBackPreview.src = e.target.result;
                            }
                            reader.readAsDataURL(this.files[0]);
                        }
                    });
                }
                
                
                // Bio character counter
                const bioTextarea = document.getElementById('bio');
                const bioCharCount = document.getElementById('bioCharCount');
                
                if (bioTextarea && bioCharCount) {
                    // Set initial count
                    bioCharCount.textContent = bioTextarea.value.length;
                    
                    bioTextarea.addEventListener('input', function() {
                        const charCount = this.value.length;
                        bioCharCount.textContent = charCount;
                        
                        if (charCount > 500) {
                            bioCharCount.classList.add('fc-text-red');
                            this.value = this.value.substring(0, 500);
                            bioCharCount.textContent = 500;
                        } else {
                            bioCharCount.classList.remove('fc-text-red');
                        }
                    });
                }
                
                // Simple radio button selection
                const categoryRadios = document.querySelectorAll('.fc-category-radio');
                categoryRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        // Update validation for step 4
                        window.validateStep4 = function() {
                            const selectedCategory = document.querySelector('input[name="category"]:checked');
                            if (!selectedCategory) {
                                alert('Please select a category before completing your profile.');
                                return false;
                            }
                            return true;
                        };
                    });
                });
            @endif
        }
    });


    document.getElementById('profileForm').addEventListener('submit', function(e) {
    let errors = [];
    // Step 1
    if (!document.getElementById('firstname').value.trim()) errors.push('First name is required.');
    if (!document.getElementById('lastname').value.trim()) errors.push('Last name is required.');
    if (!document.getElementById('email').value.trim()) errors.push('Email is required.');
    if (!document.getElementById('contact_number').value.trim()) errors.push('Contact number is required.');
    if (!document.getElementById('profilePictureInput').files.length) errors.push('Profile picture is required.');
    // Step 2
    if (!document.getElementById('province').value.trim()) errors.push('Province is required.');
    if (!document.getElementById('city').value.trim()) errors.push('City is required.');
    if (!document.getElementById('zipcode').value.trim()) errors.push('Zipcode is required.');
    // Step 3
    if (!document.getElementById('id_front').files.length) errors.push('ID front image is required.');
    if (!document.getElementById('id_back').files.length) errors.push('ID back image is required.');
    // Step 4
    if (!document.querySelector('input[name="category"]:checked')) errors.push('Category selection is required.');

    // Show errors if any
    let errorBox = document.getElementById('profileFormErrors');
    if (!errorBox) {
        errorBox = document.createElement('div');
        errorBox.id = 'profileFormErrors';
        errorBox.className = 'fc-error-message mb-2 text-red-500';
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