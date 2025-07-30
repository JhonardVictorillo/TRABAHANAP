<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\User; // Add this import

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
             'description' => 'nullable|string|max:1000',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        
        ],[
            'name.required' => 'The category name is required.',
            'name.unique' => 'This category name is already taken.'
        ]);

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image_path' => $request->file('image') ? $request->file('image')->store('categories', 'public') : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully!',
            'category' => $category
        ]);
    }
   

     // Edit method
     public function edit($id)
     {
         $category = Category::findOrFail($id);
         return response()->json($category); // Return the category data as JSON
     }

      // Update method
   public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:categories,name,' . $id,
        'description' => 'nullable|string|max:1000',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
    ], [
        'name.required' => 'The category name is required.',
        'name.unique' => 'This category name is already taken.',
    ]);

    $category = Category::findOrFail($id);
    
    $updateData = [
        'name' => $request->name,
        'description' => $request->description,
    ];
    
    // Handle image upload if present
    if ($request->hasFile('image')) {
        // Delete old image if it exists
        if ($category->image_path && Storage::disk('public')->exists($category->image_path)) {
            Storage::disk('public')->delete($category->image_path);
        }
        
        $updateData['image_path'] = $request->file('image')->store('categories', 'public');
    }
    
    $category->update($updateData);

    return response()->json([
        'success' => true,
        'message' => 'Category updated successfully!',
        'category' => $category
    ]);
}
     // Delete method
     public function destroy($id)
     {
         $category = Category::findOrFail($id);
         $category->delete();
 
         return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!'
        ]);
     }


public function getUsers(Category $category)
{
    try {
        $users = $category->users()
            ->select('users.id', 'users.firstname', 'users.lastname', 'users.email', 'users.profile_picture')
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->firstname . ' ' . $user->lastname,
                    'email' => $user->email,
                    'avatar' => $user->profile_picture ? asset('storage/' . $user->profile_picture) : null
                ];
            });
        
        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching category users: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch users: ' . $e->getMessage()
        ], 500);
    }
}
}
