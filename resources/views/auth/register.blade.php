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
                        <div class="input-field contact-field" style="position: relative;">
                            <i class='bx bx-id-card' aria-hidden="true" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #666;"></i>
                            <span class="contact-prefix" style="position: absolute; left: 2.5rem; top: 50%; transform: translateY(-50%); font-weight: 500; color: #333;">+63</span>
                            <input type="tel" name="contact_number" id="contact_number"
                                placeholder="9123456789"
                                maxlength="10"
                                pattern="[0-9]{10}"
                                value="{{ old('contact_number') }}"
                                required autocomplete="tel"
                                style="padding-left: 4.5rem;">
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
                          <span>
                                I agree to the 
                                <a href="#" id="showTerms">Terms of Service</a> 
                                and 
                                <a href="#" id="showPrivacy">Privacy Policy</a>
                            </span>
                        </label>
                        @error('terms')
                            <div class="error" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="signup-btn">
                     <span class="btn-text">Create Account</span>
                        <span class="btn-spinner" style="display:none;">
                            <i class='bx bx-loader bx-spin'></i>
                        </span>
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

    <!-- Terms Modal -->
<div id="termsModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" id="closeTerms">&times;</span>
       <h2>Terms of Service</h2>
            <p>
                Welcome to MinglaGawa! By creating an account and using our platform, you agree to the following terms:
            </p>
            <ul>
                <li><strong>Eligibility:</strong> You must be at least 18 years old to use our services.</li>
                <li><strong>Account Responsibility:</strong> You are responsible for maintaining the confidentiality of your account and password.</li>
                <li><strong>Service Use:</strong> You agree to use MinglaGawa only for lawful purposes and not to engage in any fraudulent or harmful activity.</li>
                <li><strong>Payments:</strong> All payments and transactions must be made through the platform’s approved methods.</li>
                <li><strong>Platform Commission:</strong> MinglaGawa charges a commission fee on each completed transaction. The commission is automatically deducted from the freelancer’s earnings before payout. The current commission rate is <strong>5%</strong> of the total service amount, but this may change with notice.</li>
                <li><strong>Content:</strong> You are responsible for any content you post. Do not post anything illegal, offensive, or infringing.</li>
                <li><strong>Termination:</strong> We reserve the right to suspend or terminate your account for violations of these terms.</li>
                <li><strong>Changes:</strong> MinglaGawa may update these terms at any time. Continued use of the platform means you accept the new terms.</li>
            </ul>
            <p>
                For questions, contact us at support@minglagawa.com.
            </p>
    </div>
</div>

<!-- Privacy Modal -->
<div id="privacyModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" id="closePrivacy">&times;</span>
        <h2>Privacy Policy</h2>
        <p>
            MinglaGawa values your privacy. This policy explains how we collect, use, and protect your information:
        </p>
        <ul>
            <li><strong>Information Collection:</strong> We collect personal information such as your name, email, contact number, and payment details when you register or use our services.</li>
            <li><strong>Use of Information:</strong> Your information is used to provide and improve our services, process payments, and communicate with you.</li>
            <li><strong>Sharing:</strong> We do not sell your personal information. We may share it with trusted third parties only as necessary to operate the platform (e.g., payment processors).</li>
            <li><strong>Security:</strong> We implement security measures to protect your data from unauthorized access.</li>
            <li><strong>Cookies:</strong> MinglaGawa uses cookies to enhance your experience. You can disable cookies in your browser settings.</li>
            <li><strong>Access & Correction:</strong> You may access and update your personal information in your account settings.</li>
            <li><strong>Changes:</strong> We may update this policy. We will notify you of significant changes via email or platform notice.</li>
        </ul>
        <p>
            For privacy concerns, contact us at privacy@minglagawa.com.
        </p>
    </div>
</div>


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

        
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('showTerms').onclick = function(e) {
        e.preventDefault();
        document.getElementById('termsModal').style.display = 'block';
    };
    document.getElementById('showPrivacy').onclick = function(e) {
        e.preventDefault();
        document.getElementById('privacyModal').style.display = 'block';
    };
    document.getElementById('closeTerms').onclick = function() {
        document.getElementById('termsModal').style.display = 'none';
    };
    document.getElementById('closePrivacy').onclick = function() {
        document.getElementById('privacyModal').style.display = 'none';
    };
    // Optional: close modal when clicking outside content
    window.onclick = function(event) {
        if (event.target == document.getElementById('termsModal')) {
            document.getElementById('termsModal').style.display = 'none';
        }
        if (event.target == document.getElementById('privacyModal')) {
            document.getElementById('privacyModal').style.display = 'none';
        }
    };
});


document.addEventListener('DOMContentLoaded', function () {
    const signupForm = document.getElementById('signupForm');
    const signupBtn = document.querySelector('.signup-btn');
    const btnText = signupBtn.querySelector('.btn-text');
    const btnSpinner = signupBtn.querySelector('.btn-spinner');

    signupForm.addEventListener('submit', function(e) {
        signupBtn.disabled = true;
        signupBtn.classList.add('disabled');
        btnText.style.display = 'none';
        btnSpinner.style.display = 'inline-block';
    });
});
</script>
</body>
</html>