<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Mingla Gawa</title>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <link rel="stylesheet" href="{{ asset('css/homeHeader.css') }}">
    
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
</head>
<body>
   @include('homepage.homepageHeader')

    <main class="auth-container">
        <div class="welcome-section" aria-labelledby="welcome-heading">
            <div class="welcome-content">
                <h2 id="welcome-heading">Welcome to MinglaGawa!</h2>
                <p>Start your journey with us and discover skilled freelancers near you.</p>
                <span class="highlight">Simple. Fast. Secure.</span>
            </div>
        </div>

        <div class="signup-box">
            <div class="signup-content">
                <h1>Create Account</h1>
                <p class="subtitle">Join our growing community of local talents and clients in MinglaGawa.</p>

                <form action="{{ route('register') }}" method="POST" id="signupForm" novalidate>
                    @csrf
                    
                    <!-- Role Selection Section -->
                    <div class="form-group">
                        <h3>I want to join as:</h3>
                        <div class="role-selection-container">
                            <div class="role-option">
                                <input type="radio" name="role" id="role-customer" value="customer"  required aria-describedby="customer-desc">
                                <label for="role-customer">
                                    <i class='bx bx-shopping-bag role-icon' aria-hidden="true"></i>
                                    <div class="role-title">Customer</div>
                                    <div class="role-description" id="customer-desc">I want to hire freelancers and find local services</div>
                                </label>
                            </div>
                            
                            <div class="role-option">
                                <input type="radio" name="role" id="role-freelancer" value="freelancer" required aria-describedby="freelancer-desc">
                                <label for="role-freelancer">
                                    <i class='bx bx-briefcase role-icon' aria-hidden="true"></i>
                                    <div class="role-title">Freelancer</div>
                                    <div class="role-description" id="freelancer-desc">I want to offer my services and get hired locally</div>
                                </label>
                            </div>
                        </div>
                        @error('role')
                            <div class="error" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <div class="input-field">
                                <i class='bx bx-user' aria-hidden="true"></i>
                                <input type="text" name="firstname" id="firstname" placeholder="First Name" value="{{ old('firstname') }}" required autocomplete="given-name">
                              
                            </div>
                              @error('firstname')
                                    <div class="error" role="alert">{{ $message }}</div>
                                @enderror
                        </div>
                        <div class="form-group">
                            <div class="input-field">
                                <i class='bx bx-user' aria-hidden="true"></i>
                                <input type="text" name="lastname" id="lastname" placeholder="Last Name" value="{{ old('lastname') }}" required autocomplete="family-name">
                                
                            </div>
                            @error('lastname')
                                    <div class="error" role="alert">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-field">
                            <i class='bx bx-envelope' aria-hidden="true"></i>
                            <input type="email" name="email" id="email" placeholder="Email Address" value="{{ old('email') }}" required autocomplete="email"> 
                        </div>
                         @error('email')
                            <div class="error" role="alert">{{ $message }}</div>
                            @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-field">
                            <i class='bx bx-id-card' aria-hidden="true"></i>
                            <input type="tel" name="contact_number" id="contact_number" placeholder="Contact Number" value="{{ old('contact_number') }}" required autocomplete="tel">
                        </div>
                         @error('contact_number')
                            <div class="error" role="alert">{{ $message }}</div>
                            @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-field">
                            <i class='bx bx-lock-alt' aria-hidden="true"></i>
                            <input type="password" name="password" id="passwordField" placeholder="Password" required autocomplete="new-password">
                            <span id="togglePassword" class="password-toggle-text" tabindex="0" role="button" aria-label="Show password">Show</span>
                        </div>
                        @error('password')
                            <div class="error" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-field">
                            <i class='bx bx-lock-alt' aria-hidden="true"></i>
                            <input type="password" name="password_confirmation" id="passwordConfirmField" placeholder="Confirm Password" required autocomplete="new-password">
                            <span id="togglePasswordConfirm" class="password-toggle-text" tabindex="0" role="button" aria-label="Show confirm password">Show</span>
                        </div>
                    </div>

                    <div class="terms">
                        <label for="terms">
                            <input type="checkbox" id="terms" name="terms" required>
                            <span>I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></span>
                        </label>
                        @error('terms')
                            <div class="error" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="signup-btn">
                        Create Account
                        <i class='bx bx-right-arrow-alt' aria-hidden="true"></i>
                    </button>
                </form>

                <p class="signin-prompt">
                    Already have an account? 
                    <a href="{{route('login')}}">Login</a>
                </p>
            </div>
        </div>
    </main>


    @include('homepage.homepageFooter')

    @include('successMessage')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
   
    

            // Password toggle functionality
            const passwordField = document.getElementById('passwordField');
            const togglePassword = document.getElementById('togglePassword');

            togglePassword.addEventListener('click', function () {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                this.textContent = type === 'password' ? 'Show' : 'Hide';
            });

            const passwordConfirmField = document.getElementById('passwordConfirmField');
            const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');

            togglePasswordConfirm.addEventListener('click', function () {
                const type = passwordConfirmField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmField.setAttribute('type', type);
                this.textContent = type === 'password' ? 'Show' : 'Hide';
            });
        });
    </script>
</body>
</html>