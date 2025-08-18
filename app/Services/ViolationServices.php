<?php

namespace App\Services;

use App\Models\User;
use App\Models\Violation;
use App\Models\ViolationAction;
use App\Models\ViolationSetting;
use App\Notifications\ViolationNotification;
use Illuminate\Support\Facades\DB;

class ViolationService
{
    /**
     * Process a violation and apply consequences based on violation count
     */
    public function processViolation(User $user, string $violationType, int $appointmentId = null): Violation
    {
        // Create violation record
        $violation = Violation::create([
            'user_id' => $user->id,
            'user_role' => $user->current_mode,
            'violation_type' => $violationType,
            'appointment_id' => $appointmentId,
            'status' => 'active',
        ]);
        
        // Update user counters
        if ($violationType === 'no_show') {
            $user->increment('no_show_count');
        } elseif ($violationType === 'late_cancellation') {
            $user->increment('late_cancel_count');
        }
        $user->increment('violation_count');
        $user->last_violation_at = now();
        $user->save();
        
        // Get violation count for this user within last 30 days
        $recentCount = Violation::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
            
        // Get settings for this user role
        $settings = ViolationSetting::where('user_role', $user->current_mode)->first();
        
        if (!$settings) {
            return $violation;
        }
        
        // Apply consequences based on violation count
        if ($recentCount >= $settings->ban_threshold) {
            $this->banUser($user, $violation);
        } elseif ($recentCount >= $settings->suspension_threshold) {
            $this->suspendUser($user, $violation, $settings->suspension_days);
        } elseif ($recentCount >= $settings->restriction_threshold) {
            $this->restrictUser($user, $violation);
        } elseif ($recentCount >= $settings->warning_threshold) {
            $this->warnUser($user, $violation);
        }
        
        return $violation;
    }
    
    /**
     * Send warning to user
     */
    private function warnUser(User $user, Violation $violation): void
    {
        $action = ViolationAction::create([
            'violation_id' => $violation->id,
            'action_type' => 'warning',
            'notes' => 'Automatic warning for ' . str_replace('_', ' ', $violation->violation_type),
        ]);
        
        try {
            $user->notify(new ViolationNotification($violation, $action));
        } catch (\Exception $e) {
            \Log::error('Failed to send warning notification: ' . $e->getMessage());
        }
    }
    
    /**
     * Apply restrictions to user
     */
    private function restrictUser(User $user, Violation $violation): void
    {
        // Apply restriction for 7 days
        $user->applyRestrictions(7);
        
        $action = ViolationAction::create([
            'violation_id' => $violation->id,
            'action_type' => 'restrict',
            'notes' => 'Automatic restrictions applied due to multiple violations',
            'action_data' => [
                'restriction_days' => 7,
                'restriction_end' => $user->restriction_end->format('Y-m-d H:i:s'),
            ],
        ]);
        
        try {
            $user->notify(new ViolationNotification($violation, $action));
        } catch (\Exception $e) {
            \Log::error('Failed to send restriction notification: ' . $e->getMessage());
        }
    }
    
    /**
     * Suspend user
     */
    private function suspendUser(User $user, Violation $violation, int $days = 7): void
    {
        // Suspend user
        $user->suspend($days);
        
        $action = ViolationAction::create([
            'violation_id' => $violation->id,
            'action_type' => 'suspension',
            'notes' => "Automatic {$days}-day suspension due to multiple violations",
            'action_data' => [
                'days' => $days,
                'suspension_end' => $user->suspended_until->format('Y-m-d H:i:s'),
            ],
        ]);
        
        try {
            $user->notify(new ViolationNotification($violation, $action));
        } catch (\Exception $e) {
            \Log::error('Failed to send suspension notification: ' . $e->getMessage());
        }
    }
    
    /**
     * Ban user permanently
     */
    private function banUser(User $user, Violation $violation): void
    {
        // Ban user
        $user->ban('Repeated violations exceeding maximum threshold');
        
        $action = ViolationAction::create([
            'violation_id' => $violation->id,
            'action_type' => 'ban',
            'notes' => 'Automatic permanent ban due to excessive violations',
        ]);
        
        try {
            $user->notify(new ViolationNotification($violation, $action));
        } catch (\Exception $e) {
            \Log::error('Failed to send ban notification: ' . $e->getMessage());
        }
    }
}