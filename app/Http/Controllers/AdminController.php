<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category; // Import the Category model


class AdminController extends Controller
{
   public function dashboard(){
        $totalFreelancers = User::where('role', 'freelancer')->count();
        $totalCustomers = User::where('role', 'customer')->count();

         // Retrieve all users for the Users table section
         $users = User::all();
     
           // Retrieve all categories for the Categories section
        $categories = Category::all();
         
        
    return View('dashboard.admin-dashboard', [
            'totalFreelancers' => $totalFreelancers,
            'totalCustomers' => $totalCustomers,
            'users' => $users,
               'categories' => $categories,
        ]);
    
   }

  
}
