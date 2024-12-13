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

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Category created successfully!');
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

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

     // Delete method
     public function destroy($id)
     {
         $category = Category::findOrFail($id);
         $category->delete();
 
         return redirect()->back()->with('success', 'Category deleted successfully!');
     }
}
