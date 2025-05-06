<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;

class ForgotPasswordController extends Controller
{
   
    public function showLinkRequestForm()
    {
        return view('auth.login');  // Ensure this view exists
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'The email address you entered is not registered.'
        ]);

        // Get the user by email
        $user = User::where('email', $request->email)->first();

        // Generate a plain token
        $token = Str::random(60);

        // Store the token in the password_resets table
        $user->reset_token = $token;
        $user->reset_token_expires_at = now()->addMinutes(60); // Token expires in 60 minutes
        $user->save();

        // Generate the reset link
        $resetLink = route('password.reset', ['token' => $token, 'email' => $user->email]);

        // Send the password reset email
        Mail::to($user->email)->send(new ResetPasswordMail($resetLink));

        // Return with status
        return back()->with('status', 'Password reset link sent to your email!');
    }

    public function validateEmail(Request $request)
{
    // Validate the email format
    $request->validate([
        'email' => 'required|email',
    ]);

    // Check if the email exists in the database
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['status' => 'error', 'message' => 'This email does not exist in our records.']);
    }

    // Check if the email is verified
    if (!$user->email_verified_at) {
        return response()->json(['status' => 'error', 'message' => 'This email is not verified.']);
    }

    // If the email exists and is verified
    return response()->json(['status' => 'success', 'message' => 'This email is valid and verified.']);
}
}
