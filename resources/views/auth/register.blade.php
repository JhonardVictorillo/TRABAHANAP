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
    
    <div class="wrapper">
        <a href="{{route('homepage')}}"><i class='bx bx-x close-icon'></i></a>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <h1>Register</h1>
            <div class="input-box">
                <div class="input-field">
                    <input type="text" name="firstname" placeholder="Firstname" value="{{ old('firstname') }}" required>
                    @error('firstname')
                    <div class="error">{{ $message }}</div>
                @enderror
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-field">
                    <input type="text" name="lastname" placeholder="Lastname"  value="{{ old('lastname') }}" required>
                    @error('lastname')
                    <div class="error">{{ $message }}</div>
                @enderror
                    <i class='bx bxs-user'></i>
                </div>
            </div>
            <div class="input-box">
                <div class="input-field">
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    @error('email')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <i class='bx bxl-gmail'></i>
                </div>
                <div class="input-field">
                    <input type="number" name="contact_number" placeholder="Contact Number" value="{{old('contact_number')}}"required>
                    @error('contact_number')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <i class='bx bxs-phone' ></i>
                </div>
            </div>
            <div class="input-box">
                <div class="input-field">
                    <input type="password"  id="password" name="password" placeholder="Password" value="{{ old('password') }}" required>
                    @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
                    <i class='bx bxs-lock-alt' ></i>
                </div>
                <div class="input-field">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                    <i class='bx bxs-lock-alt' ></i>
                </div>
              
                <button type="submit" class="registerbtn">Register</button>
                @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
            </div>
        </form>
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