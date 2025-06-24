<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Skip profile check for login and registration routes
        if ($request->routeIs('profile.complete') || 
            $request->routeIs('login') || 
            $request->routeIs('register') || 
            $request->routeIs('select.role')) {
            return $next($request);
        }

        // If profile is not complete, redirect to the dashboard 
        // (where the modal will appear)
        if ($user && !$user->profile_completed) {
            if ($user->role === 'freelancer') {
                return redirect()->route('freelancer.dashboard');
            } elseif ($user->role === 'customer') {
                return redirect()->route('customer.dashboard');
            }
        }
        
        return $next($request);
    }
}
