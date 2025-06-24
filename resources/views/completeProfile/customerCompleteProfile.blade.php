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
                            <label for="google_map_link">Google Maps Link (Optional)</label>
                            <input type="url" name="google_map_link" id="google_map_link" placeholder="Paste your Google Maps link" value="{{ old('google_map_link', $user->google_map_link ?? '') }}">
                            @error('google_map_link')
                                <div class="cc-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Map Preview -->
                    <div id="mapPreview" class="cc-map-preview" style="display:none">
                        <h4><i class='bx bx-map-pin'></i> Map Location Preview</h4>
                        <iframe id="embeddedMap" width="100%" height="250" frameborder="0" style="border:0; border-radius: 8px;" allowfullscreen="" loading="lazy"></iframe>
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
            
            // Google Maps preview
            const mapInput = document.getElementById('google_map_link');
            const mapPreview = document.getElementById('mapPreview');
            const embeddedMap = document.getElementById('embeddedMap');
            
            if (mapInput && mapPreview && embeddedMap) {
                mapInput.addEventListener('input', function() {
                    const url = mapInput.value;
                    
                    if (url.includes('maps.app.goo.gl') || url.includes("google.com/maps")) {
                        try {
                            const embedUrl = convertToEmbedURL(url);
                            embeddedMap.src = embedUrl;
                            mapPreview.style.display = 'block';
                        } catch (e) {
                            mapPreview.style.display = 'none';
                            embeddedMap.src = '';
                        }
                    } else {
                        mapPreview.style.display = 'none';
                        embeddedMap.src = '';
                    }
                });
                
                function convertToEmbedURL(url) {
                    if (url.includes("maps.app.goo.gl")) {
                        // Short URL handling
                        return `https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.802548850901!2d121.04882897485761!3d14.554743382575185!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c8efd99aad53%3A0xb64b39847a866fde!2sMakati%20City%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1698123909569!5m2!1sen!2sph`;
                    }
                    
                    if (url.includes("google.com/maps")) {
                        const match = url.match(/@(.*),(.*),([\d\.]*)z/);
                        if (match) {
                            const lat = match[1];
                            const lng = match[2];
                            return `https://www.google.com/maps/embed/v1/view?key=YOUR_API_KEY&center=${lat},${lng}&zoom=14&maptype=roadmap`;
                        } else if (url.includes("place")) {
                            return url.replace("/maps/place/", "/maps/embed/v1/place?key=YOUR_API_KEY&q=");
                        } else if (url.includes("embed")) {
                            return url;
                        }
                    }
                    
                    throw new Error("Invalid map URL");
                }
            }
        @endif
    });
</script>