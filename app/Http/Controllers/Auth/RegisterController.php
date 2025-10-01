<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Add this line to import the User model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail; // Ensure you have this Mailable
class RegisterController extends Controller
{

    public function showRegistrationForm()
    {
        return view('auth.register'); // Ensure this view exists
    }
    
      public function register(Request $request)
    {
        // Validate the incoming request data - ADD ROLE TO VALIDATION
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
           'email' => [
                        'required',
                        'string',
                        'email',
                        'max:255',
                        'unique:users',
                        'regex:/^[A-Za-z0-9._%+-]+@gmail\.com$/'
                    ],
            'password' => 'required|string|min:8|confirmed',
            'contact_number' => 'required|string|regex:/^[0-9]{10}$/',
            'role' => 'required|string|in:customer,freelancer', // Add this line
        ],[
            'email.unique' => 'The email address is already in use.',
            'contact_number.regex' => 'The contact number must be 10 digits.',
            'role.required' => 'Please select whether you want to join as a customer or freelancer.',
        ]);
        
        // Create a new user in the database - SAVE ROLE DURING REGISTRATION
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact_number' => $request->contact_number,
            'role' => $request->role, // Save role from the form
            'current_mode' => $request->role, // Set current_mode to match the role
            'email_verification_token' => Str::random(60),
        ]);

        Mail::to($user->email)->send(new VerifyEmail($user));

        return redirect()->route('login')->with('success', 'Account created successfully! Please check your email to verify your account.');
    }

    public function verifyEmail($token)
    {
        try {
            // Find the user by the email verification token
            $user = User::where('email_verification_token', $token)->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Redirect with an error if the token is invalid or expired
            return redirect()->route('login')->withErrors(['token' => 'Invalid or expired verification token.']);
        }

        // Mark the user's email as verified
        $user->email_verified_at = now();
        $user->email_verification_token = null; // Clear the token
        $user->save();

        // Redirect to login or home with a success message
        return redirect()->route('login')->with('status', 'Your email has been successfully verified.');
    }
}
