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
                                <input type="text" name="firstname" id="firstname" placeholder="First Name" value="{{ old('firstname') }}" required autocomplete="given-name" pattern="[A-Za-z\s'-]+" ...>
                              
                            </div>
                            <div class="error" id="error-firstname"></div>
                        </div>
                        <div class="form-group">
                            <div class="input-field">
                                <i class='bx bx-user' aria-hidden="true"></i>
                                <input type="text" name="lastname" id="lastname" placeholder="Last Name" value="{{ old('lastname') }}" required autocomplete="family-name" pattern="[A-Za-z\s'-]+" ...>
                                
                            </div>
                            <div class="error" id="error-lastname"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-field">
                            <i class='bx bx-envelope' aria-hidden="true"></i>
                           <input type="email" name="email" id="email" placeholder="Email Address"
                                value="{{ old('email') }}" required autocomplete="email"
                                pattern="^[A-Za-z0-9._%+-]+@gmail\.com$"
                                title="Only Gmail addresses are allowed (e.g. yourname@gmail.com)">
                        </div>
                        <div class="error" id="error-email"></div>
                    </div>

                 <div class="form-group">
                        <div class="input-field contact-field" style="position: relative;">
                            <i class='bx bx-id-card' aria-hidden="true" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #666;"></i>
                            <span class="contact-prefix" style="position: absolute; left: 2.5rem; top: 50%; transform: translateY(-50%); font-weight: 500; color: #333;">+63</span>
                            <input type="tel" name="contact_number" id="contact_number"
                             
                                maxlength="10"
                                pattern="[0-9]{10}"
                                value="{{ old('contact_number') }}"
                                required autocomplete="tel"
                                style="padding-left: 4.5rem;">
                        </div>
                        <div class="error" id="error-contact_number"></div>
                    </div>
                    <div class="form-group">
                        <div class="input-field">
                            <i class='bx bx-lock-alt' aria-hidden="true"></i>
                            <input type="password" name="password" id="passwordField" placeholder="Password" required autocomplete="new-password">
                            <span id="togglePassword" class="password-toggle-text" tabindex="0" role="button" aria-label="Show password">Show</span>
                        </div>
                        <div class="error" id="error-password"></div>
                    </div>

                    <div class="form-group">
                        <div class="input-field">
                            <i class='bx bx-lock-alt' aria-hidden="true"></i>
                            <input type="password" name="password_confirmation" id="passwordConfirmField" placeholder="Confirm Password" required autocomplete="new-password">
                            <span id="togglePasswordConfirm" class="password-toggle-text" tabindex="0" role="button" aria-label="Show confirm password">Show</span>
                        
                        </div>
                         <div class="error" id="error-password_confirmation"></div>
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
        <h2 style="border: none; border-top: 2px solid #2563eb22; margin: 0.7rem 0 1.2rem 0;">Terms of Service</h2>
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
        <h2 style="border: none; border-top: 2px solid #2563eb22; margin: 0.7rem 0 1.2rem 0;">Privacy Policy</h2>
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
    const signupForm = document.getElementById('signupForm');
    const signupBtn = document.querySelector('.signup-btn');
    const btnText = signupBtn.querySelector('.btn-text');
    const btnSpinner = signupBtn.querySelector('.btn-spinner');
    const termsCheckbox = document.getElementById('terms');

    // Field references
    const fields = {
        firstname: document.getElementById('firstname'),
        lastname: document.getElementById('lastname'),
        email: document.getElementById('email'),
        contact_number: document.getElementById('contact_number'),
        password: document.getElementById('passwordField'),
        password_confirmation: document.getElementById('passwordConfirmField')
    };

    // Validation rules
    function validateField(name, value) {
        switch(name) {
            case 'firstname':
            case 'lastname':
                if (!value.trim()) return 'This field is required.';
                if (value.length > 255) return 'Maximum 255 characters.';
                if (!/^[A-Za-z\s'-]+$/.test(value)) return 'Only letters, apostrophes, and hyphens are allowed.';
                break;
            case 'email':
                if (!value.trim()) return 'Email is required.';
                if (!/^[A-Za-z0-9._%+-]+@gmail\.com$/.test(value)) return 'Only Gmail addresses are allowed (e.g. yourname@gmail.com)';
                break;
            case 'contact_number':
                if (!value.trim()) return 'Contact number is required.';
                if (!/^[0-9]{10}$/.test(value)) return 'Enter a valid 10-digit number.';
                break;
            case 'password':
                if (!value) return 'Password is required.';
                if (value.length < 8) return 'Password must be at least 8 characters.';
                break;
            case 'password_confirmation':
                if (value !== fields.password.value) return 'Passwords do not match.';
                break;
        }
        return '';
    }

    // NEW: Validate role selection
    function validateRole() {
        const roleSelected = document.querySelector('input[name="role"]:checked');
        return roleSelected ? '' : 'Please select your role (Customer or Freelancer).';
    }

    // NEW: Validate terms checkbox
    function validateTerms() {
        return termsCheckbox.checked ? '' : 'You must agree to the Terms of Service and Privacy Policy.';
    }

    // Live validation for fields
    Object.keys(fields).forEach(function(field) {
        fields[field].addEventListener('input', function() {
            const errorDiv = document.getElementById('error-' + field);
            const errorMsg = validateField(field, this.value);
            errorDiv.textContent = errorMsg;
            errorDiv.style.display = errorMsg ? 'block' : 'none';
            checkFormValidity();
        });
    });

    // NEW: Live validation for role selection
    document.querySelectorAll('input[name="role"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            checkFormValidity();
        });
    });

    // NEW: Live validation for terms checkbox
    termsCheckbox.addEventListener('change', function() {
        checkFormValidity();
    });

    // Updated: Check overall form validity including role and terms
    function checkFormValidity() {
        let hasError = false;
        
        // Check field errors
        Object.keys(fields).forEach(function(field) {
            const errorDiv = document.getElementById('error-' + field);
            if (errorDiv && errorDiv.textContent) hasError = true;
        });
        
        // NEW: Check role and terms
        if (validateRole() || validateTerms()) hasError = true;
        
        signupBtn.disabled = hasError;
        if (hasError) {
            signupBtn.classList.add('disabled');
        } else {
            signupBtn.classList.remove('disabled');
        }
    }

    // Updated: On submit validation including role and terms
    signupForm.addEventListener('submit', function(e) {
        let hasError = false;
        
        // Validate all fields
        Object.keys(fields).forEach(function(field) {
            const errorDiv = document.getElementById('error-' + field);
            const errorMsg = validateField(field, fields[field].value);
            errorDiv.textContent = errorMsg;
            errorDiv.style.display = errorMsg ? 'block' : 'none';
            if (errorMsg) hasError = true;
        });
        
        // NEW: Validate role selection
        const roleError = validateRole();
        if (roleError) {
            hasError = true;
            alert(roleError); // Show alert for role error
        }
        
        // NEW: Validate terms agreement
        const termsError = validateTerms();
        if (termsError) {
            hasError = true;
            alert(termsError); // Show alert for terms error
        }
        
        if (hasError) {
            e.preventDefault();
            signupBtn.disabled = false;
            signupBtn.classList.remove('disabled');
            btnText.style.display = 'inline-block';
            btnSpinner.style.display = 'none';
        } else {
            // Form is valid, show loading state
            signupBtn.disabled = true;
            signupBtn.classList.add('disabled');
            btnText.style.display = 'none';
            btnSpinner.style.display = 'inline-block';
        }
    });

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

    // Initial form validity check
    checkFormValidity();
});

// Modal functionality (keep as separate DOMContentLoaded)
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
    
    // Close modal when clicking outside content
    window.onclick = function(event) {
        if (event.target == document.getElementById('termsModal')) {
            document.getElementById('termsModal').style.display = 'none';
        }
        if (event.target == document.getElementById('privacyModal')) {
            document.getElementById('privacyModal').style.display = 'none';
        }
    };
});
</script>
</body>
</html>