<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostPicture;
use App\Models\PostSubService;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(Request $request)
    {  
         // ADD THIS SECTION: Check if user can create posts
    $user = auth()->user();
    
    if ($user->is_suspended || $user->is_banned) {
        $message = $user->is_suspended 
            ? 'Your account is currently suspended until ' . $user->suspended_until->format('M d, Y') . '.' 
            : 'Your account has been banned due to policy violations.';
            
        return response()->json([
            'success' => false,
            'errors' => ['restriction' => ['Unable to create service. ' . $message . ' Please contact support for assistance.']]
        ], 403);
    }
    
    // Check for restrictions
    if ($user->is_restricted && (!$user->restriction_end || now()->lessThan($user->restriction_end))) {
        return response()->json([
            'success' => false,
            'errors' => ['restriction' => ['Your account is currently restricted from creating new services due to policy violations. Restrictions will be lifted on ' . 
                $user->restriction_end->format('M d, Y') . '. Please contact support for more information.']]
        ], 403);
    }
    
        $validator = \Validator::make($request->all(), [
            'description' => 'required|string',
            'sub_services' => 'required|array|min:1',
            'sub_services.*' => 'required|string|max:255',
            'post_picture' => 'required|array|min:1',
            'post_picture.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
             'rate' => 'required|numeric|min:0',
             'rate_type' => 'required|in:hourly,daily,fixed',
              'location_restriction' => 'required|in:minglanilla_only,open',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        try {
            DB::beginTransaction();

            // Create Post
            $post = Post::create([
                'freelancer_id' => Auth::id(),
                'description' => $request->description,
                'status' => 'pending',
                'rate' => $request->rate,
                'rate_type' => $request->rate_type,
                 'location_restriction' => $request->location_restriction, 
            ]);

            // Store SubServices
            foreach ($request->sub_services as $sub_service) {
                PostSubService::create([
                    'post_id' => $post->id,
                    'sub_service' => $sub_service,
                   
                ]);
            }

            // Store Post Pictures
            if ($request->hasFile('post_picture')) {
                foreach ($request->file('post_picture') as $file) {
                    $path = $file->store('post_pictures', 'public');
                    PostPicture::create([
                        'post_id' => $post->id,
                        'image_path' => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Error creating post: ' . $e->getMessage()
            ]);
        }
    }

    public function index()
    {
        $posts = Post::with(['freelancer', 'categories', 'subServices', 'pictures'])->get();
        return view('posts.index', compact('posts'));
    }

    public function edit($id)
    {
        try {
            \Log::info('Fetching post data for Post ID: ' . $id);
            $post = Post::with(['pictures', 'subServices'])->findOrFail($id);

            if ($post->freelancer_id !== Auth::id()) {
                \Log::error('Unauthorized access to post ID: ' . $id);  
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            return response()->json([
                'post' => $post,
                'sub_services' => $post->subServices->pluck('sub_service'),
                'post_pictures' => $post->pictures->pluck('image_path'),
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching post data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch post data'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->freelancer_id !== auth()->id()) { 
            return response()->json(['success' => false, 'error' => 'Unauthorized action.'], 403);
        }

        $validator = \Validator::make($request->all(), [
            'description' => 'required|string',
            'sub_services' => 'required|array|min:1',
            'sub_services.*' => 'required|string|max:255',
            'post_picture.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'rate' => 'required|numeric|min:0',
             'rate_type' => 'required|in:hourly,daily,fixed',
             'location_restriction' => 'required|in:minglanilla_only,open',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        try {
            DB::beginTransaction();

            $post->description = $request->description;
            $post->rate = $request->rate;           // <-- Add this
            $post->rate_type = $request->rate_type;
            $post->location_restriction = $request->location_restriction; 
            $post->save();

            // Update SubServices
            PostSubService::where('post_id', $post->id)->delete();
            foreach ($request->sub_services as $sub_service) {
                PostSubService::create([
                    'post_id' => $post->id,
                    'sub_service' => $sub_service,
                ]);
            }

            // Handle Image Deletion

            if ($request->has('delete_images')) {
                $deleteImages = explode(',', $request->delete_images);
                foreach ($deleteImages as $imagePath) {
                    // Delete image from storage
                    $imagePath = ltrim($imagePath, 'public/'); // Ensure correct path format
                    if (Storage::exists('public/post_pictures/' . $imagePath)) {
                        Storage::delete('public/post_pictures/' . $imagePath);
                    }
                }
    
                // Remove the deleted images from the post's relationship table (e.g., pictures)
                foreach ($deleteImages as $imagePath) {
                    $post->pictures()->where('image_path', $imagePath)->delete();
                }
            }
    
            // Handle Image Uploads
            if ($request->hasFile('post_picture')) {
                foreach ($request->file('post_picture') as $file) {
                    $path = $file->store('post_pictures', 'public');
                    PostPicture::create([
                        'post_id' => $post->id,
                        'image_path' => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Post updated successfully!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'error' => 'Failed to update post.', 
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $post = Post::findOrFail($id);

            if ($post->freelancer_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }

            // Delete related subservices and pictures
            PostSubService::where('post_id', $post->id)->delete();
            $postPictures = PostPicture::where('post_id', $post->id)->get();

            foreach ($postPictures as $picture) {
                \Storage::disk('public')->delete($picture->image_path);
                $picture->delete();
            }

            $post->delete(); 

            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting post: ' . $e->getMessage()
            ], 500);
        }
    }
}
