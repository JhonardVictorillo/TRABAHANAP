<?php

namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ],[
            'name.required' => 'The category name is required.',
            'name.unique' => 'This category name is already taken.'
        ]);

        $category = Category::create([
            'name' => $request->name,
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
        ], [
            'name.required' => 'The category name is required.',
            'name.unique' => 'This category name is already taken.',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
        ]);

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
}
