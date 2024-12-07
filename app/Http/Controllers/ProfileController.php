<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{       
        // Show the complete profile form for freelancers
    public function showFreelancerProfileForm()
    {       
        $user = Auth::user();
        $categories = Category::all(); 
        return view('completeProfile.freelancerCompleteProfile',[
            'user' => $user,
            'categories' => $categories,
                       
        ]); // Create this Blade view
    }

    // Show the complete profile form for customers
    public function showCustomerProfileForm()
    {
        $user = Auth::user();
        return view('completeProfile.customerCompleteProfile',[
            'user' => $user, 
        ]); // Create this Blade view
    }


    // Show the complete profile form
    public function submitCompleteProfileForm(Request $request)
    {       
        $validatedData = $request->validate([
           
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email',
            'contact_number' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'zipcode' => 'required|string',
            'google_map_link' => 'nullable|url',
            'valid_id_front' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Restrict to images and size limit
            'valid_id_back' => 'required|image|mimes:jpg,jpeg,png|max:2048',  // Restrict to images and size limit
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048', // New validation for profile picture
        ]);
        \Log::info('Validated Data:', $validatedData);
        // Get the authenticated user
        $user = Auth::user();

        // Update user profile details
    
        $user->firstname = $validatedData['firstname'];
        $user->lastname = $validatedData['lastname'];
        $user->email = $validatedData['email'];
        $user->contact_number = $validatedData['contact_number'];
        $user->province = $validatedData['province'];
        $user->city = $validatedData['city'];
        $user->zipcode = $validatedData['zipcode'];
        $user->google_map_link = $validatedData['google_map_link'];

        // Handle file uploads and store only the paths
        if ($request->hasFile('valid_id_front')) {
            $idFrontPath = $request->file('valid_id_front')->store('id_uploads', 'public');
          
            $user->id_front = $idFrontPath;
        }

        if ($request->hasFile('valid_id_back')) {
            $idBackPath = $request->file('valid_id_back')->store('id_uploads', 'public');
           
            $user->id_back = $idBackPath;
        }
          // Handle profile picture upload
          if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            
            $user->profile_picture = $profilePicturePath; // Save the path to the user's profile
        }


        
        // Mark the profile as completed
        $user->profile_completed = true;
      
        $user->save();

        // Add category selection only if the user is a freelancer
      if ($user->role === 'freelancer') {
        $validatedData['category'] = $request->validate([
            'category' => 'required|array|min:1',
            'category.*' => 'integer',
        ])['category'];

        $user->categories()->sync($validatedData['category']);
      }
        // $user->categories()->sync($request->category);
        // $user->categories()->attach(
        //     $request->category,
        //     ['created_at' => now(), 'updated_at' => now()]
        // );
        // Set the session variable to indicate the profile is complete
        session(['profile_completed' => true]);


        if ($user->role === 'freelancer') {
            return redirect()->route('freelancer.dashboard')->with('profile_completed', true)->with('success', 'Profile completed successfully!');
        } elseif ($user->role === 'customer') {
            return redirect()->route('customer.dashboard')->with('profile_completed', true)->with('success', 'Profile completed successfully!');
        }

        // Fallback for unknown roles
        return redirect()->route('homepage')->with('success', 'Profile completed successfully!');
    }
}
