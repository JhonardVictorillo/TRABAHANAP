<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Post; // Add this for the Post model
use Illuminate\Support\Facades\DB; // Add this for database transactions

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {  
        // dd($request->all());

        $request->validate([
            'description' => 'required|string',
            'sub_services' => 'required|array|min:1',
            'sub_services.*' => 'required|string|max:255',
            'post_picture' => 'required|array|min:1',
            'post_picture.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        try {
            DB::beginTransaction();
    
            // Create Post
            $post = Post::create([
                'freelancer_id' => Auth::id(),
                'description' => $request->description,
                'sub_services' => json_encode($request->sub_services),
            ]);
    
            if (empty($request->sub_services) || count(array_filter($request->sub_services)) === 0) {
                return back()->withErrors(['sub_services' => 'At least one sub-service is required.']);
            }
            
            if (!$request->hasFile('post_picture')) {
                return back()->withErrors(['post_picture' => 'At least one post picture is required.']);
            }
            // File Upload
            if ($request->hasFile('post_picture')) {
                $uploadedPaths = [];
                foreach ($request->file('post_picture') as $file) {
                    $path = $file->store('post_pictures', 'public');
                    $uploadedPaths[] = $path;
                }
                $post->post_picture = json_encode($uploadedPaths);
                $post->save();
            }
    
            DB::commit();
    
            return redirect()->route('freelancer.dashboard')->with('success', 'Post created successfully!')->with('post-section');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating post: ' . $e->getMessage());
        }
    }
    

    public function index()
{
    $posts = Post::with('freelancer')->get(); // Retrieve posts with related freelancer data
    return view('posts.index', compact('posts'));
}

public function edit($id)
{
    try {
        \Log::info('Fetching post data for Post ID: ' . $id);
        $post = Post::findOrFail($id);

        // Ensure the logged-in freelancer owns the post
        if ($post->freelancer_id !== Auth::id()) {
            \Log::error('Unauthorized access to post ID: ' . $id);  
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if sub_services is already an array, no need to decode
        $sub_services = $post->sub_services;
        if (is_string($sub_services)) {
            $sub_services = json_decode($sub_services, true);
            // Handle JSON decode errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::error('Invalid JSON for sub_services of post ID: ' . $id);
                $sub_services = [];  // If invalid JSON, return empty array
            }
        }
        return response()->json([
            'post' => $post,
            'sub_services' => $sub_services
        ]);
    } catch (\Exception $e) {
        // Log the error
        \Log::error('Error fetching post data: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch post data'], 500);
    }
}

public function update(Request $request, $id)
{
    \Log::info('Updating post: ', $request->all());
    $post = Post::findOrFail($id);

    // Ensure the logged-in freelancer owns the post
    if ($post->freelancer_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    $request->validate([
        'description' => 'required|string',
        'sub_services' => 'required|array|min:1',
        'sub_services.*' => 'required|string|max:255',
        'post_picture.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    try {
        DB::beginTransaction();

        $post->description = $request->description;
        $post->sub_services = json_encode($request->sub_services);

        // Update post pictures if new images are uploaded
        if ($request->hasFile('post_picture')) {
            $uploadedPaths = [];
            foreach ($request->file('post_picture') as $file) {
                $path = $file->store('post_pictures', 'public');
                $uploadedPaths[] = $path;
            }
            $post->post_picture = json_encode($uploadedPaths);
        }

        $post->save();

        DB::commit();

        return redirect()->route('freelancer.dashboard')->with('success', 'Post updated successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error updating post: ' . $e->getMessage());
    }
}


}