<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Return your login view
    }

    public function login(Request $request)
    {
         // Validate the login form data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
            // Throttle key based on the user's email and IP address
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        // Check if the user has exceeded the maximum login attempts
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => 'Too many login attempts. Please try again in ' . ceil($seconds / 60) . ' minutes.',
            ])->withInput();
        }


        $credentials = $request->only('email', 'password');

        // Attempt to log the user in...
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

      // Get the authenticated user
      $user = Auth::user();  // This will get the currently authenticated user

          // Check if the user is banned
        if ($user->is_banned) {
          Auth::logout();
          return redirect()->back()->withErrors([
              'email' => 'Your account has been banned. Please contact support for more information.',
          ])->withInput();
        }



      // Check if the user's email is verified
      if (is_null($user->email_verified_at)) {
          Auth::logout(); // Log the user out
          return redirect()->back()->withErrors([
              'email' => 'Your email is not verified. Please check your inbox and verify your email.',
          ])->withInput();
      }

            // Check if the user has a role assigned
            if (Auth::user()->role === null) {
              session()->flash('success', 'Welcome! Please select your role to continue.');
                return redirect()->route('select.role');
        }
      // Redirect to the dashboard based on the user's role
        if (Auth::user()->role === 'freelancer') {
          session()->flash('success', 'Welcome back, Freelancer ' . Auth::user()->firstname . '!');
        return redirect()->route('freelancer.dashboard');
      } elseif (Auth::user()->role === 'customer') {
        session()->flash('success', 'Welcome back, ' . Auth::user()->firstname . '!');
        return redirect()->route('customer.dashboard');
      }elseif(Auth::user()->role === 'admin'){
        session()->flash('success', 'Welcome, Admin ' . Auth::user()->firstname . '!');
        return redirect()->route('admin.dashboard');
    } 

    }

     // Increment the throttle key on failed login
     RateLimiter::hit($throttleKey, 60); // Lockout for 60 seconds after 5 attempts

             return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
                ])->withInput()
                ->with('openModal', 'loginModal'); 

    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
