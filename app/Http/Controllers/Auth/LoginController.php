<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
        $credentials = $request->only('email', 'password');

        // Attempt to log the user in...
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

       

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
             return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);

    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
