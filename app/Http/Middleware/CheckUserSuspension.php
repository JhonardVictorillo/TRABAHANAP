<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class CheckUserSuspension
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Check if user is suspended
            if ($user->is_suspended) {
                // Check if suspension has expired
                if ($user->suspended_until && Carbon::parse($user->suspended_until)->isPast()) {
                    // Suspension period is over, remove suspension
                    $user->is_suspended = false;
                    $user->suspended_until = null;
                    $user->save();
                } else {
                    // User is still suspended
                    auth()->logout();
                    
                    if ($request->expectsJson()) {
                        return response()->json([
                            'error' => 'Your account is currently suspended', 
                            'suspended_until' => $user->suspended_until
                        ], 403);
                    }
                    
                    $message = 'Your account is suspended';
                    if ($user->suspended_until) {
                        $message .= ' until ' . Carbon::parse($user->suspended_until)->format('M d, Y');
                    }
                    $message .= '. Please contact support for assistance.';
                    
                    return redirect()->route('login')->with('error', $message);
                }
            }
            
            // Check if user is banned
            if ($user->is_banned) {
                auth()->logout();
                
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Your account has been banned'], 403);
                }
                
                return redirect()->route('login')
                    ->with('error', 'Your account has been banned. Please contact support for assistance.');
            }
        }
        
        return $next($request);
    }
}