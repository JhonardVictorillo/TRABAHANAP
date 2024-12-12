<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
@if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="login-form">
        <h1>Trabahanap.</h1>
        <div class="container">
            <div class="main">
                <div class="content">
                   <a href="{{route('homepage')}}"><i class='bx bx-x close-icon'></i></a>
                    <h2>Welcome Back!</h2>

                    <form action="{{ route('login.submit') }}" method="POST">
                    @csrf
                        <input type="text" id="email" name="email" placeholder="email" required autofocus="">
                        @error('email')
                        <div class="error"style="font-size:0.8rem">{{ $message }}</div>
                          @enderror
                    
                        <input type="password" id="password" name="password" placeholder="Password">
                        @error('password')
                         <div class="error" style="font-size:0.8rem">{{ $message }}</div>
                         @enderror
                        <button class="btn" type="submit">Login</button>
                    </form>
                    <p class="account">Don't Have An Account? <a href="{{route('register')}}">Register</a></p>
                </div>
                <div class="form-img">
                    <img src="images/loginpic.png" alt="">
                </div>
            </div>
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