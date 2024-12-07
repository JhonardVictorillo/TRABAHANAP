<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Show the role selection form.
     */
    public function showRoleForm()
    {
        return view('auth.select-role');
    }

    /**
     * Save the selected role and redirect to the appropriate dashboard.
     */
    public function saveRole(Request $request)
    {
        $request->validate([
            'role' => 'required|in:freelancer,customer'
        ]);

        $user = Auth::user();
        $user->role = $request->input('role');
        $user->save();

        // Redirect to profile completion page if the profile is incomplete
    if (!$user->profile_completed) {
        if ($user->role === 'freelancer') {
            return redirect()->route('profile.freelancer');
        } elseif ($user->role === 'customer') {
            return redirect()->route('profile.customer');
        }
    }
    
        if ($user->role === 'freelancer') {
            return redirect()->route('freelancer.dashboard')->with('success', 'Role selected successfully!');
        } elseif ($user->role === 'customer') {
            return redirect()->route('customer.dashboard')->with('success', 'Role selected successfully!');
        }

        // Fallback if role doesn't match
        return redirect()->route('select.role')->with('error', 'Please select a valid role.');
    }
}
