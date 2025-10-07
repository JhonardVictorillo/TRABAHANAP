<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Notifications\CategoryRequestProcessedNotification;

class CategoryRequestController extends Controller
{
    public function index()
    {
        // Get all requests with related user info
        $requests = CategoryRequest::with(['user', 'processor'])
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.category-requests.index', compact('requests'));
    }
    
    /**
     * Show details of a specific request
     */
   public function show($id)
{
    try {
        $request = CategoryRequest::with(['user', 'processor'])->findOrFail($id);
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'request' => $request
            ]);
        }
        
        return view('admin.category-requests.show', compact('request'));
    } catch (\Exception $e) {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Request not found',
                'error' => $e->getMessage()
            ], 404);
        }
        
        return abort(404);
    }
}
    
    /**
     * Approve a category request
     */
    public function approve(Request $request, $id)
{
    try {
        // Log the request for debugging
        \Log::info('Category request approval attempt', [
            'id' => $id,
            'request_data' => $request->all()
        ]);
        
        $categoryRequest = CategoryRequest::findOrFail($id);
        
        // Only process pending requests
        if ($categoryRequest->status !== 'pending') {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed.'
                ]);
            }
            return redirect()->back()->with('error', 'This request has already been processed.');
        }
        
        // For AJAX requests, get the category name from the request data
        if ($request->ajax()) {
            $categoryName = $request->input('category_name', $categoryRequest->category_name);
            $adminNotes = $request->input('admin_notes') ?? 'Approved via admin dashboard';
        } else {
            // Validate input
            $validatedData = $request->validate([
                'category_name' => 'required|string|max:255',
                'admin_notes' => 'nullable|string',
            ]);
            
            $categoryName = $validatedData['category_name'];
            $adminNotes = $validatedData['admin_notes'];
        }
        
        // Check if similar category already exists (case insensitive)
        $similarCategory = Category::whereRaw('LOWER(name) = ?', [strtolower($categoryName)])->first();
        
        if ($similarCategory) {
            // Use the existing category
            $category = $similarCategory;
        } else {
            // Create new category
            $category = new Category();
            $category->name = $categoryName;
            $category->save();
        }
        
        // Update the request status
        $categoryRequest->status = 'approved';
        $categoryRequest->admin_notes = $adminNotes;
        $categoryRequest->processed_by = Auth::id();
        $categoryRequest->processed_at = Carbon::now();
        $categoryRequest->save();
        
        // Add the category to the user's selected categories
        $user = User::find($categoryRequest->user_id);
        if ($user) {
        
            try {
                $user->notify(new CategoryRequestProcessedNotification($categoryRequest, true));
            } catch (\Exception $e) {
                \Log::warning('Failed to send notification for approved category request', [
                    'user_id' => $user->id,
                    'request_id' => $categoryRequest->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category request approved successfully.',
                'category' => $category,
                'request' => $categoryRequest->load('user', 'processor')
            ]);
        }
        
        return redirect()->route('admin.category-requests.index')
            ->with('success', 'Category request approved successfully.');
    } catch (\Exception $e) {
        // Log the error with detailed information
        \Log::error('Error approving category request', [
            'id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing this request.',
                'error' => $e->getMessage()
            ], 500);
        }
        
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}
    
    /**
     * Decline a category request
     */
   public function decline(Request $request, $id)
{
    try {
        // Log the request for debugging
        \Log::info('Category request decline attempt', [
            'id' => $id,
            'request_data' => $request->all()
        ]);
        
        $categoryRequest = CategoryRequest::findOrFail($id);
        
        // Only process pending requests
        if ($categoryRequest->status !== 'pending') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed.'
                ]);
            }
            return redirect()->back()->with('error', 'This request has already been processed.');
        }
        
        // For AJAX requests
        $adminNotes = $request->input('admin_notes', 'Declined via admin dashboard');
        
        // Update the request status
        $categoryRequest->status = 'declined';
        $categoryRequest->admin_notes = $adminNotes;
        $categoryRequest->processed_by = Auth::id();
        $categoryRequest->processed_at = Carbon::now();
        $categoryRequest->save();
        
        // Notify the user
        $user = User::find($categoryRequest->user_id);
        if ($user) {
            try {
                $user->notify(new CategoryRequestProcessedNotification($categoryRequest, false));
            } catch (\Exception $e) {
                \Log::warning('Failed to send notification for declined category request', [
                    'user_id' => $user->id,
                    'request_id' => $categoryRequest->id,
                    'error' => $e->getMessage()
                ]);
                // Continue processing - don't throw the error to the user
            }
        }
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category request declined successfully.',
                'request' => $categoryRequest->load('user', 'processor')
            ]);
        }
        
        return redirect()->route('admin.category-requests.index')
            ->with('success', 'Category request declined successfully.');
    } catch (\Exception $e) {
        // Log the error with detailed information
        \Log::error('Error declining category request', [
            'id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing this request.',
                'error' => $e->getMessage()
            ], 500);
        }
        
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}
public function pendingCount()
{
    try {
        $pendingCount = CategoryRequest::where('status', 'pending')->count();
        $approvedCount = CategoryRequest::where('status', 'approved')->count();
        $declinedCount = CategoryRequest::where('status', 'declined')->count();
        $totalCount = CategoryRequest::count();
        
        return response()->json([
            'count' => $pendingCount,
            'approvedCount' => $approvedCount,
            'declinedCount' => $declinedCount,
            'totalCount' => $totalCount
        ]);
    } catch (\Exception $e) {
        \Log::error('Error getting pending count', [
            'error' => $e->getMessage()
        ]);
        
        return response()->json([
            'count' => 0,
            'approvedCount' => 0,
            'declinedCount' => 0,
            'totalCount' => 0,
            'error' => $e->getMessage()
        ], 500);
    }
}
}

