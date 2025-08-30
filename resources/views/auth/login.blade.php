<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In - Mingla Gawa</title>
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('css/login.css')}}">
   <link rel="stylesheet" href="{{asset('css/homeHeader.css')}}">
   <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet' />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
 
</head>

<body>
  @include('successMessage')
  @include('homepage.homepageHeader')
     

    <main class="auth-container">
    <div class="welcome-section">
      <div class="welcome-content">
        <h2>Welcome back to MinglaGawa!</h2>
        <p> Connect with local workers or manage your service requests.</p>
        <span class="highlight">Simple. Fast. Secure.</span>
      </div>
    </div>

    <div class="signin-box">
      <div class="signin-content">
        <h1>Login</h1>
        <p class="subtitle">Enter your credentials to access your account.</p>

        <!-- <div class="social-signin">
          <button class="social-btn google">
            <i class='bx bxl-google'></i>
            Continue with Google
          </button>
          <button class="social-btn facebook">
            <i class='bx bxl-facebook'></i>
            Continue with Facebook
          </button>
        </div> -->

        <form action="{{ route('login') }}" method="POST" id="signinForm">
          @csrf
          <div class="form-group">
            <div class="input-field">
              <i class='bx bx-envelope'></i>
              <input type="email" name="email" placeholder="Email Address" required>
            </div>
            @error('email')
                <div class="error">{{ $message }}</div>
                    @enderror
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

          <!-- Moved forgot password link to below the password field (left aligned) -->
          <div class="forgot-password-container">
            <a href="#" id="forgotPasswordLink">Forgot Password?</a>
          </div>


          <button type="submit" class="signin-btn">
              <span class="btn-text">Login</span>
              <span class="btn-spinner" style="display:none;">
                  <i class='bx bx-loader bx-spin'></i>
              </span>
              <i class='bx bx-right-arrow-alt'></i>
          </button>
        </form>
        
        <p class="signup-prompt">
          Don't have an account? 
          <a href="{{ route('register.form') }}">Sign up</a>
        </p>
      </div>
    </div>
  </main>

  <!-- Forgot Password Modal -->
<div id="forgotPasswordModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <p>Enter your email address to reset your password.</p>
        <form id="forgotPasswordForm" method="POST" action="{{ route('password.email') }}">
            @csrf
            <input type="email" id="forgotPasswordEmail" name="email" placeholder="Email" required>
            <div id="emailValidationMessage" class="error" style="display: none;"></div>
            <button type="submit" id="sendResetLinkBtn" disabled>Send Reset Link</button>
        </form>
    </div>
</div>

  @include('homepage.homepageFooter')
  
  <script>

 
    // Modal functionality
    const modal = document.getElementById('forgotPasswordModal');
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');
    const closeModal = document.getElementById('closeModal');

    forgotPasswordLink.addEventListener('click', function(e) {
      e.preventDefault();
      modal.style.display = 'block';
    });

    closeModal.addEventListener('click', function() {
      modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });

    document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('forgotPasswordEmail');
    const validationMessage = document.getElementById('emailValidationMessage');
    const submitButton = document.getElementById('sendResetLinkBtn');

    emailInput.addEventListener('input', function () {
        const email = emailInput.value;

        // Hide validation message and disable submit button while typing
        validationMessage.style.display = 'none';
        submitButton.disabled = true;

        // Skip validation if the email field is empty
        if (!email) return;

        // Send AJAX request to validate the email
        fetch('{{ route('validate.email') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ email: email }),
        })
            .then(response => response.json())
            .then(data => {
                validationMessage.style.display = 'block';
                validationMessage.textContent = data.message;

                if (data.status === 'success') {
                    validationMessage.style.color = 'green';
                    submitButton.disabled = false;
                } else {
                    validationMessage.style.color = '#d9534f'; // Red for errors
                    submitButton.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});
    //password toggle functionality
    document.addEventListener('DOMContentLoaded', function () {
        const passwordField = document.getElementById('passwordField');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Toggle the text
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
    const signinForm = document.getElementById('signinForm');
    const signinBtn = document.querySelector('.signin-btn');
    const btnText = signinBtn.querySelector('.btn-text');
    const btnSpinner = signinBtn.querySelector('.btn-spinner');

    signinForm.addEventListener('submit', function(e) {
        signinBtn.disabled = true;
        signinBtn.classList.add('disabled');
        btnText.style.display = 'none';
        btnSpinner.style.display = 'inline-block';
    });
});
  </script>
</body>
</html>


