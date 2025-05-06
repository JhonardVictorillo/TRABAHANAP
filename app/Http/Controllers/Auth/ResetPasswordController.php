<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail; // Ensure you have this Mailable




class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token)
    {
        // Show the reset password form with the token and email
        return view('auth.resetpasswordform', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        // Validate the input
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        // Check if the token exists in the password_resets table
        $user = User::where('email', $request->email)
        ->where('reset_token', $request->token)
        ->where('reset_token_expires_at', '>', now())
        ->first();

        if (!$user) {
            return back()->withErrors(['token' => 'The reset token is invalid or has expired.']);
        }

       // Update the user's password
        $user->password = Hash::make($request->password);
        $user->reset_token = null; // Clear the reset token
        $user->reset_token_expires_at = null; // Clear the expiration time
        $user->save();


       

        // Redirect to login with success message
        return redirect()->route('login')->with('status', 'Your password has been reset successfully!');
    }

    
}


