<!DOCTYPE html>
<html>
<head>
    <title>Reset Your Password</title>
</head>
<body>
    <h1>Hello!</h1>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <p>Please click the button below to reset your password:</p>
    
    <a href="{{ $resetLink }}" 
       style="background-color: #4CAF50; 
              color: white; 
              padding: 10px 15px; 
              margin: 8px 0; 
              border: none; 
              cursor: pointer; 
              text-decoration: none;">
        Reset Password
    </a>

    <p>If you did not request a password reset, no further action is required.</p>
    <p>This password reset link will expire in 60 minutes.</p>
</body>
</html>