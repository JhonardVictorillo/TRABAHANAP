<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
  
    public function showRoleForm()
    {
        // If user already has a role, redirect them to the appropriate dashboard
        $user = Auth::user();
        if ($user->role) {
            if ($user->role === 'freelancer') {
                return redirect()->route('freelancer.dashboard');
            } else {
                return redirect()->route('customer.dashboard');
            }
        }
        
        // Only show the role selection form to users without a role
        return view('auth.select-role');
    }

    /**
     * Save the selected role and redirect to the appropriate dashboard.
     */
    public function saveRole(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|in:freelancer,customer',
        ]);

        $user = Auth::user();
        $user->role = $validated['role'];
        $user->current_mode = $validated['role']; // Also set current_mode
        $user->save();

        // Redirect to the appropriate dashboard
        if ($validated['role'] === 'freelancer') {
            return redirect()->route('freelancer.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    }
}
