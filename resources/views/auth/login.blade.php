<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
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



            <form action="{{ route('login.submit') }}" method="POST">
                    @csrf

                <input type="email" name="email" class="input-box" placeholder="Email" required>
                @error('email')
                <div class="error"style="font-size:0.8rem">{{ $message }}</div>
                    @enderror
                <input type="password" name="password" class="input-box" placeholder="Password" required>
                @error('password')
                <div class="error" style="font-size:0.8rem">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn">Sign In</button>
            </form>
            <p class="account">Don't Have An Account?  <a href="{{route('register')}}">Register</a></p>

        </div>
        <div class="signup-section">
            <h2>Welcome Back</h2>
            <p>Welcome back! Please enter your personal details</p>
            <img src="images/signin.png" alt="">
        </div>
    </div>


</body>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const alert = document.querySelector('.alert-success');
    if (alert) {
        setTimeout(() => {
            alert.remove();
        }, 3000); // 3 seconds
    }
});
    </script>



</html>    


