<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Mingla Gawa</title>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{'css/register.css'}}">
</head>
<body>
    <header class="header">
        <div class="logo-header">
          Mingla<span class="brand-span">Gawa</span>
        </div>
        <nav class="navbar">
          <a href="{{route('homepage')}}">Home</a>
          <a href="#category">Categories</a>
          <a href="#how-it-works">How It Works</a>
          <a href="#contact">Contact</a>
          <a href="{{route('login')}}"><button class="logbtn">Login</button></a>
          <a href="{{route('register.form')}}"><button class="active">Register</button></a>
        </nav>
      </header>

    <main class="auth-container">
        <div class="welcome-section">
            <div class="welcome-content">
                <h2>Welcome to Mingla Gawa!</h2>
                <p>Start your journey with us and discover skilled freelancers near you.</p>
                <span class="highlight">Simple. Fast. Secure.</span>
                
            </div>
        </div>

        <div class="signup-box">
            <div class="signup-content">
                <h1>Create Account</h1>
                <p class="subtitle">Join our growing community of local talents and clients in MinglaGawa.</p>


                <div class="social-signup">
                    <button class="social-btn google">
                        <i class='bx bxl-google'></i>
                        Continue with Google
                    </button>
                    <button class="social-btn facebook">
                        <i class='bx bxl-facebook'></i>
                        Continue with Facebook
                    </button>
                </div>

                <form action="{{ route('register') }}" method="POST" id="signupForm">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <div class="input-field">
                                <i class='bx bx-user'></i>
                                <input type="text" name="firstname" placeholder="First Name" required>
                                @error('firstname')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-field">
                                <i class='bx bx-user'></i>
                                <input type="text" name="lastname" placeholder="Last Name" required>
                                @error('lastname')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-field">
                            <i class='bx bx-envelope'></i>
                            <input type="email" name="email" placeholder="Email Address" required>
                            @error('email')
                            <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-field">
                            <i class='bx bx-id-card'></i>
                            <input type="text" name="contact_number" placeholder="Contact Number" required>
                            @error('contact_number')
                            <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                            <div class="input-field">
                                <i class='bx bx-lock-alt'></i>
                                <input type="password" name="password" id="passwordField" placeholder="Password" required>
                                <span id="togglePassword" class="password-toggle-text" style="cursor: pointer;">Show</span>
                            </div>
                            @error('password')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="input-field">
                                <i class='bx bx-lock-alt'></i>
                                <input type="password" name="password_confirmation" id="passwordConfirmField" placeholder="Confirm Password" required>
                                <span id="togglePasswordConfirm" class="password-toggle-text" style="cursor: pointer;">Show</span>
                            </div>
                          
                        </div>

                    <div class="terms">
                        <label>
                            <input type="checkbox" required>
                            <span>I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></span>
                            <div class="error-message"></div>
                        </label>
                    </div>

                    <button type="submit" class="signup-btn">
                        Create Account
                        <i class='bx bx-right-arrow-alt'></i>
                    </button>
                </form>

                <p class="signin-prompt">
                    Already have an account? 
                    <a href="{{route('login')}}">Register</a>
                </p>
            </div>
        </div>
    </main>
        @if(session('success'))
    <div class="alert alert-success">
    <i class='bx bx-check-circle'></i> <!-- Success icon -->
    {{ session('success') }}
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const alert = document.querySelector('.alert-success');
        if (alert) {
            setTimeout(() => {
            alert.style.display = 'none';
            }, 3000); // 3 seconds
        }
        });


        // password toggle functionality
        document.addEventListener('DOMContentLoaded', function () {
        // Toggle for the main password field
        const passwordField = document.getElementById('passwordField');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Toggle the text
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });

        // Toggle for the confirm password field
        const passwordConfirmField = document.getElementById('passwordConfirmField');
        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');

        togglePasswordConfirm.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordConfirmField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmField.setAttribute('type', type);

            // Toggle the text
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });
    });
    </script>
</body>
</html>

    


   