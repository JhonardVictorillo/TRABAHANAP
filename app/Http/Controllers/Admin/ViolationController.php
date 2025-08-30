<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Violation;
use App\Models\ViolationAction;
use App\Models\ViolationSetting;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ViolationNotification;

class ViolationController extends Controller
{
    public function getDetails($violationId)
{
    // Find the violation instead of appointment
    $violation = Violation::find($violationId);
    
    if (!$violation) {
        return response()->json(['success' => false, 'message' => 'Violation not found']);
    }
    
    $user = User::find($violation->user_id);
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found']);
    }
    
    // Get violation count for this user
    $violationCount = Violation::where('user_id', $user->id)->count();
    
    // Determine violation type text
    $violationType = ucfirst(str_replace('_', ' ', $violation->violation_type));
    
    return response()->json([
        'success' => true,
        'user_name' => $user->firstname . ' ' . $user->lastname,
        'violation_type' => $violationType,
        'violation_count' => $violationCount
    ]);
}

    /**
     * Send a warning notification to the user.
     */
 public function sendWarning(Request $request)
{
    $violationId = $request->violation_id;
    
    $violation = Violation::find($violationId);
    
    if (!$violation) {
        return response()->json(['success' => false, 'message' => 'Violation not found']);
    }
    
    $user = User::find($violation->user_id);
    
    // Record the warning action
    $action = ViolationAction::create([
        'violation_id' => $violation->id,
        'action_type' => 'warning',
        'notes' => 'Warning for ' . str_replace('_', ' ', $violation->violation_type),
        'admin_id' => auth()->id()
    ]);
    
    // Send notification to user
    try {
        $user->notify(new ViolationNotification($violation, $action));
    } catch (\Exception $e) {
        \Log::error('Failed to send violation notification: ' . $e->getMessage());
        
        return response()->json([
            'success' => false, 
            'message' => 'Error sending notification: ' . $e->getMessage()
        ]);
    }
    
    return response()->json(['success' => true]);
}

    
     public function toggleSuspension(Request $request)
    {
       $violationId = $request->violation_id;
    
    $violation = Violation::find($violationId);
    
    if (!$violation) {
        return response()->json(['success' => false, 'message' => 'Violation not found']);
    }
    
    $user = User::find($violation->user_id);
        
        // Check if user is already suspended
        $isSuspended = $user->is_suspended;
        
        // Get the settings for this user role
        $settings = ViolationSetting::where('user_role', $role)->first();
        $suspensionDays = $settings ? $settings->suspension_days : 7;
        
        if ($isSuspended) {
            // Remove suspension
            $user->is_suspended = false;
            $user->suspended_until = null;
            $user->save();
            
            return response()->json([
                'success' => true, 
                'message' => 'User has been unsuspended.'
            ]);
        } else {
            // Apply suspension
            $user->is_suspended = true;
            $user->suspended_until = now()->addDays($suspensionDays);
            $user->save();
            
            // Record violation if doesn't exist
            $violation = Violation::firstOrCreate(
                ['appointment_id' => $appointmentId, 'user_id' => $user->id],
                [
                    'user_role' => $role,
                    'violation_type' => 'no_show',
                    'status' => 'active',
                ]
            );
            
            // Record the suspension action
            $action = ViolationAction::create([
                'violation_id' => $violation->id,
                'action_type' => 'suspension',
                'notes' => 'Account suspended due to no-show',
                'action_data' => ['days' => $suspensionDays],
                'admin_id' => auth()->id()
            ]);
            
            // Send notification to user
            try {
                $user->notify(new ViolationNotification($violation, $action));
            } catch (\Exception $e) {
                // Log the error but don't stop execution
                \Log::error('Failed to send suspension notification: ' . $e->getMessage());
            }
            
            return response()->json([
                'success' => true, 
                'message' => "User has been suspended for {$suspensionDays} days."
            ]);
        }
    }
    
      public function applyAction(Request $request)
    {
        $violationId = $request->violation_id;
        $action = $request->action;
        $notes = $request->notes;
    
    $violation = Violation::find($violationId);
    
    if (!$violation) {
        return response()->json(['success' => false, 'message' => 'Violation not found']);
    }
    
    $user = User::find($violation->user_id);
        
      
        // Update user violation count
        $user->violation_count += 1;
        $user->no_show_count += 1;
        $user->last_violation_at = now();
        
        // Prepare action data
        $actionData = [];
        
        switch($action) {
            case 'fee':
                $actionData['fee_amount'] = $request->fee_amount;
                $violation->status = 'fee_applied';
                break;
                
            case 'suspend':
                $days = $request->suspension_days ?? 7;
                $actionData['days'] = $days;
                $violation->status = 'suspended';
                
                // Apply the suspension
                $user->is_suspended = true;
                $user->suspended_until = now()->addDays($days);
                break;
                
            case 'ban':
                $violation->status = 'banned';
                
                // Permanently ban the user
                $user->is_suspended = true;
                $user->is_banned = true;
                break;
                
            case 'restrict':
             
                $actionData['restrictions'] = ['booking_limit' => true];
                $violation->status = 'restricted';

                // Actually apply the restriction to the user
                $user->is_restricted = true;
                $user->restriction_end = now()->addDays(7); // or your desired duration
                $user->restriction_reason = $notes;
                break;
                
            case 'warning':
                $violation->status = 'warned';
                break;
        }
        
        $user->save();
        $violation->save();
        
        // Record the action
        $violationAction = ViolationAction::create([
            'violation_id' => $violation->id,
            'action_type' => $action,
            'notes' => $notes,
            'action_data' => $actionData,
            'admin_id' => auth()->id()
        ]);
        
        // Send notification to user
        try {
            $user->notify(new ViolationNotification($violation, $violationAction));
        } catch (\Exception $e) {
            // Log the error but don't stop execution
            \Log::error('Failed to send violation action notification: ' . $e->getMessage());
        }
        
        return response()->json(['success' => true]);
    }
    
    public function saveSettings(Request $request)
{
    // Validate input
    $request->validate([
        'freelancer' => 'required|array',
        'customer' => 'required|array',
    ]);
    
    // Update freelancer settings
    $freelancerSettings = ViolationSetting::where('user_role', 'freelancer')->first();
    if (!$freelancerSettings) {
        $freelancerSettings = new ViolationSetting();
        $freelancerSettings->user_role = 'freelancer';
    }
    
    $freelancerSettings->no_show_penalties = $request->freelancer['no_show_penalties'] ?? false;
    $freelancerSettings->auto_warning = $request->freelancer['auto_warning'] ?? false;
    $freelancerSettings->rating_penalty = $request->freelancer['rating_penalty'] ?? false;
    $freelancerSettings->booking_restrictions = false; // Not applicable for freelancers
    $freelancerSettings->auto_suspension = $request->freelancer['auto_suspension'] ?? false;
    $freelancerSettings->suspension_days = $request->freelancer['suspension_days'] ?? 7;
    $freelancerSettings->warning_threshold = $request->freelancer['warning_threshold'] ?? 2;
    $freelancerSettings->restriction_threshold = $request->freelancer['restriction_threshold'] ?? 3;
    $freelancerSettings->suspension_threshold = $request->freelancer['suspension_threshold'] ?? 5;
    $freelancerSettings->ban_threshold = $request->freelancer['ban_threshold'] ?? 7;
    $freelancerSettings->save();
    
    // Update customer settings
    $customerSettings = ViolationSetting::where('user_role', 'customer')->first();
    if (!$customerSettings) {
        $customerSettings = new ViolationSetting();
        $customerSettings->user_role = 'customer';
    }
    
    $customerSettings->no_show_penalties = $request->customer['no_show_penalties'] ?? false;
    $customerSettings->auto_warning = $request->customer['auto_warning'] ?? false;
    $customerSettings->rating_penalty = false; // Not applicable for customers
    $customerSettings->booking_restrictions = $request->customer['booking_restrictions'] ?? false;
    $customerSettings->auto_suspension = $request->customer['auto_suspension'] ?? false;
    $customerSettings->suspension_days = $request->customer['suspension_days'] ?? 7;
    $customerSettings->warning_threshold = $request->customer['warning_threshold'] ?? 2;
    $customerSettings->restriction_threshold = $request->customer['restriction_threshold'] ?? 3;
    $customerSettings->suspension_threshold = $request->customer['suspension_threshold'] ?? 5;
    $customerSettings->ban_threshold = $request->customer['ban_threshold'] ?? 7;
    $customerSettings->save();
    
    return response()->json(['success' => true]);
}
};