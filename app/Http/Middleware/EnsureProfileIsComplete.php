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

        if ($user && !$user->profile_completed) {
            if ($user->role === 'freelancer') {
                return redirect()->route('profile.freelancer');
            } elseif ($user->role === 'customer') {
                return redirect()->route('profile.customer');
            }
        }
        return $next($request);
    }
}
