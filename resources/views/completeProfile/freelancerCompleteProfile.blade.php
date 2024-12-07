<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreelancerCompleteProfile</title>
    <link rel="stylesheet" href="{{asset ('css/complete-profile.css')}}">
</head>
<body>
  
    
     <!-- First Modal: Ask the user to complete their profile -->
     <div class="modal" id="completeAccountModal">
    <div class="modal-content">
        <h2>Complete Your Profile</h2>
        <p>Please complete your details to proceed to your dashboard. Note: Your account is under validation.</p>
        <button id="completeAccountBtn" type="submit">Complete Account</button>
    </div>
</div>

 <!-- Modal -->
 <div class="modal" id="completeProfileModal" style="display:  {{ $errors->any() ? 'block': ' none'}}">
        <div class="modal-content">
            <!-- Close Button -->
            <button class="close-btn" onclick="closeModal()"><i class='bx bx-x'></i></button>
            <h2>Complete Your Profile</h2>
            <form action="{{ route('profile.complete') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-container">
                    <!-- Personal Details -->
                    <h3>Personal Details</h3>
                    <div class="form-row">
                        <input type="text" name="firstname" placeholder="First Name" value="{{ old('firstname', $user->firstname) }}" required>
                     @error('firstname')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                        <input type="text" name="lastname" placeholder="Last Name" value="{{ old('lastname', $user->lastname) }}" required>
                        @error('lastname')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                        </div>
                    <div class="form-row">
                        <input type="email" name="email" placeholder="Email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                        <input type="text" name="contact_number" placeholder="Contact Number" value="{{ old('contact_number', $user->contact_number) }}" required>
                        @error('contact_number')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    </div>

                    <!-- Address -->
                    <h3>Address</h3>
                    <div class="form-row">
                        <input type="text" name="province" placeholder="Province" required>
                 @error('province')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                        <input type="text" name="city" placeholder="City" required>
                        @error('city')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-row">
                        <input type="text" name="zipcode" placeholder="Zipcode" required>
                        @error('zipcode')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                        <input type="url" name="google_map_link" placeholder="Google Maps Link">
                        @error('google_map_link')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <h3>Upload Profile Picture</h3>
                   <div class="form-row">
                    <input type="file" name="profile_picture" accept="image/*" required>
                    @error('profile_picture')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                    <!-- ID Upload -->
                    <h3>Upload Valid ID</h3>
                    <div class="form-row">
                        <label for="id_front">Front:</label>
                        <input type="file" name="valid_id_front" id="id_front" accept="image/*" required>
                        @error('valid_id_front')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-row">
                        <label for="id_back">Back:</label>
                        <input type="file" name="valid_id_back" id="id_back" accept="image/*" required>
                        @error('valid_id_back')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                  
                    <h3>Work Category</h3>
                    <div class="work-category-grid">
                    @foreach($categories as $category)
                    <label class="category-card">
                        <input type="checkbox" name="category[]" value="{{ $category->id }}" >
                        <div class="card-content">
                        <i class='bx bx-meteor'></i> <!-- You can customize the icon as needed -->
                            <p>{{ $category->name }}</p>
                        </div>
                    </label>
                @endforeach
                @error('category')
                <div class="error-message">{{ $message }}</div>
            @enderror
            </div>


                    <button type="submit">Submit Details</button>
                </div>
            </form>
        </div>
    </div>

    

    
</body>
        <script>
             document.getElementById("completeAccountBtn").addEventListener("click", function() {
            document.getElementById("completeAccountModal").style.display = 'block'; // Hide the first modal
            document.getElementById("completeProfileModal").style.display = 'block'; // Show the second modal
        });

        </script>
</html>