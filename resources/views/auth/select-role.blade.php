<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Role</title>
    <link rel="stylesheet" href="{{ asset('css/select-role.css') }}"> 
    </head>
    <body>

<div class="container">
    <h2>Select Your Role</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('save.role') }}" method="POST">
        @csrf
        <div class="selectrole">
            <label for="freelancer" class="form-group">
                <input type="radio" name="role" value="freelancer" id="freelancer" required> 
                <img src="images/freelancer.jpg" alt="Freelancer">
                <span>Freelancer</span>
            </label>

            <label for="customer" class="form-group">
                <input type="radio" name="role" value="customer" id="customer" required> 
                <img src="images/customer.jpg" alt="Customer">
                <span>Customer</span>
            </label>
        </div>
        <button type="submit" class="btn-submit">Submit</button>
    </form>
</div>

</body>
</html>