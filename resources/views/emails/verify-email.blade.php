<!DOCTYPE html>
<html>
<head>
    <title>Welcome to MinglaGawa</title>
</head>
<body>
    <h1>Welcome, {{ $user->firstname }}!</h1>
    <p>Thank you for registering with us. Please verify your email address by clicking the link below:</p>
    <a href="{{ $verificationUrl }}">Verify Email</a>
    <p>If you did not create this account, no further action is required.</p>
    <p>You can log in anytime using the following link:</p>
    <a href="{{ $loginUrl }}">Log In</a>
    <p>Thank you,<br>Your App Team</p>
</body>
</html>