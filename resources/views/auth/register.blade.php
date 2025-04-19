<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registrtation</title>
    <link rel="stylesheet" href="{{asset('css/register.css')}}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    
    @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

    <header class="header">
       
       <div class="home-header">
       <h2 class="logo-header">
           <span class="logo-icon">MG</span>
           Mingla<span class="brand-span">Gawa</span>
       </h2>          
   </div>
      
       <nav class="navbar">
           <a href="{{route('homepage')}}">Home</a>
           <a href="{{route('login')}}"><button class="logbtn">Login</button></a>
           <a href="{{route('register.form')}}"><button class="regisbtn">Register</button></a>
       </nav>
   </header>
   

                <div class="container" id="signup">
                    <div class="login-section">
                        <div class="custom-logo">
                            <img src="images/mglogo.png" alt="">
                        </div>
                        <div class="social-icons">
                            <a href="#"><i class='bx bxl-facebook'></i></a>
                            <a href="#"><i class='bx bxl-google'></i></a>
                            <a href="#"><i class='bx bxl-linkedin'></i></a>
                        </div>
                        <p>or use your account</p>



                        <form action="{{ route('register') }}" method="POST">
                        @csrf

                <input type="text" name="firstname" class="input-box" placeholder="First Name" value="{{ old('firstname') }}" required>
                @error('firstname')
                    <div class="error">{{ $message }}</div>
                @enderror
                <input type="text" name="lastname" class="input-box" placeholder="Last Name" value="{{ old('lastname') }}" required>
               
                @error('lastname')
                    <div class="error">{{ $message }}</div>
                @enderror
                <input type="email" name="email" class="input-box" placeholder="Email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                    @enderror
                <input type="text" name="contact_number" class="input-box" placeholder="Contact Number" value="{{old('contact_number')}}" required>
                @error('contact_number')
                    <div class="error">{{ $message }}</div>
                    @enderror
                <input type="password" name="password" class="input-box" placeholder="Password" value="{{ old('password') }}" required>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
                <input type="password" name="password_confirmation" class="input-box" placeholder="Confirm Password" required>
                
                <button type="submit" class="btn">Sign Up</button>
            </form>

        </div>
        <div class="signup-section">
            <h2>Hello, Friend!</h2>
            <p>Enter your personal details and start your journey with us</p>
            <img src="images/signup.png" alt="">
        </div>
    </div>





</body>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const alert = document.querySelector('.alert-success');
    if (alert) {
        setTimeout(() => {
            alert.remove();
        }, 5000); // 3 seconds
    }
});

    </script>



</html>