<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RoleSwitchController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Simple mode switch between freelancer and customer
     */
    public function switchMode(Request $request)
    {
        $mode = $request->mode;
        
        if (!in_array($mode, ['freelancer', 'customer'])) {
            return back()->with('error', 'Invalid mode selected.');
        }
        
        $user = Auth::user();
        
        // Check if user has permission for this mode
        if ($mode === 'freelancer' && $user->role !== 'freelancer' && $user->role !== 'both') {
            return back()->with('error', 'You don\'t have a freelancer account.');
        }
        
        if ($mode === 'customer' && $user->role !== 'customer' && $user->role !== 'both') {
            return back()->with('error', 'You don\'t have a customer account.');
        }
        
        // Update user's current mode
        $user->current_mode = $mode;
        $user->save();
        
        return back()->with('success', 'Switched to ' . ucfirst($mode) . ' mode.');
    }
    
    /**
     * Show form to become a freelancer
     */
    public function becomeFreelancer()
    {
        return view('account.become-freelancer');
    }
    
    /**
     * Process freelancer application
     */
    public function processFreelancerApplication(Request $request)
    {
        $request->validate([
            'skills' => 'required|array|min:1',
            'bio' => 'required|min:100',
            'experience_level' => 'required|in:beginner,intermediate,expert',
            'hourly_rate' => 'required|numeric|min:5',
            'daily_rate' => 'nullable|numeric|min:40',
            'specialization' => 'required|string|max:255',
        ]);
        
        $user = Auth::user();
        
        // Update user with freelancer information
        $user->skills = json_encode($request->skills);
        $user->bio = $request->bio;
        $user->experience_level = $request->experience_level;
        $user->hourly_rate = $request->hourly_rate;
        $user->daily_rate = $request->daily_rate ?? null;
        $user->specialization = $request->specialization;
        
        // Update role
        if ($user->role === 'customer') {
            $user->role = 'both';
        } else {
            $user->role = 'freelancer';
        }
        
        // Set additional fields
        $user->current_mode = 'freelancer';
        $user->role_updated_at = now();
        $user->freelancer_onboarded = true; // Set to true since we're collecting all info here
        
        $user->save();
        
        return redirect()->route('dashboard')
            ->with('success', 'Your freelancer account has been created successfully!');
    }
    
    /**
     * Show form to become a customer
     */
    public function becomeCustomer()
    {
        return view('account.become-customer');
    }
    
    /**
     * Process customer application
     */
    public function processCustomerApplication(Request $request)
    {
        $request->validate([
            'preferences' => 'nullable|array',
        ]);
        
        $user = Auth::user();
        
        // Update role
        if ($user->role === 'freelancer') {
            $user->role = 'both';
        } else {
            $user->role = 'customer';
        }
        
        // Set additional fields
        $user->current_mode = 'customer';
        $user->role_updated_at = now();
        $user->customer_onboarded = true; // Set to true since there's minimal onboarding
        
        // If there are preferences, store them
        if ($request->has('preferences')) {
            // Store preferences in an appropriate field
            // This depends on your specific data structure
        }
        
        $user->save();
        
        return redirect()->route('dashboard')
            ->with('success', 'Your customer account has been created successfully!');
    }
    
    /**
     * Show freelancer onboarding form - only if additional info is needed
     */
    public function freelancerOnboarding()
    {
        $user = Auth::user();
        
        // If already onboarded, redirect to dashboard
        if ($user->freelancer_onboarded) {
            return redirect()->route('dashboard');
        }
        
        return view('account.freelancer-onboarding', compact('user'));
    }
    
    /**
     * Show customer onboarding form - only if additional info is needed
     */
    public function customerOnboarding()
    {
        $user = Auth::user();
        
        // If already onboarded, redirect to dashboard
        if ($user->customer_onboarded) {
            return redirect()->route('dashboard');
        }
        
        return view('account.customer-onboarding', compact('user'));
    }
    
    /**
     * Complete freelancer onboarding - for any additional information
     */
    public function completeFreelancerOnboarding(Request $request)
    {
        $request->validate([
            'hourly_rate' => 'required|numeric|min:5',
            'daily_rate' => 'nullable|numeric|min:40',
            'bio' => 'required|string|min:100',
            // Add any other validation rules
        ]);
        
        $user = Auth::user();
        
        // Update user with additional freelancer information
        $user->hourly_rate = $request->hourly_rate;
        $user->daily_rate = $request->daily_rate;
        $user->bio = $request->bio;
        
        // Mark as onboarded
        $user->freelancer_onboarded = true;
        $user->save();
        
        return redirect()->route('dashboard')
            ->with('success', 'Your freelancer profile is now complete!');
    }
    
    /**
     * Complete customer onboarding - for any additional information
     */
    public function completeCustomerOnboarding(Request $request)
    {
        // Validate any additional customer fields
        
        $user = Auth::user();
        
        // Update any additional customer information
        
        // Mark as onboarded
        $user->customer_onboarded = true;
        $user->save();
        
        return redirect()->route('dashboard')
            ->with('success', 'Your customer profile is now complete!');
    }
}
