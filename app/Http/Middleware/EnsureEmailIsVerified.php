<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Routes that should be excluded from email verification check
     */
    protected $except = [
        '/',
        'login',
        'register',
        'home',
        'email/verify/*',
        'logout',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user's email is not verified
            if (!$user->email_verified_at) {
                // Allow access to excluded routes
                foreach ($this->except as $pattern) {
                    if ($request->is($pattern)) {
                        return $next($request);
                    }
                }
                
                // Redirect to index page with error message
                return redirect()->route('index')
                    ->with('error', 'Please verify your email address to access this feature. Check your inbox for the verification link.');
            }
        }

        return $next($request);
    }
}
