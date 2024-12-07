<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Add this line to import the User model
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    public function showRegistrationForm()
    {
        return view('auth.register'); // Ensure this view exists
    }
    
    public function register(Request $request)
    {
       
               
        // Validate the incoming request data
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
           'contact_number' => 'required|string|regex:/^[0-9]{10}$/', 
        ],[
            'email.unique' => 'The email address is already in use.',
            'contact_number.regex' => 'The contact number must be 10 digits.',


        ]);
        
          // Create a new user in the database
        User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),  // Encrypt the password
            'contact_number' => $request->contact_number, 
            'role' => null, // Explicitly set the role to null
        ]);

        return redirect()->route('login')->with('success', 'Account created successfully! Please log in.');
    }
}
