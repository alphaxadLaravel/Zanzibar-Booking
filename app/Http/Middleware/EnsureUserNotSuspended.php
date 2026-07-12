<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserNotSuspended
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user() ?? Auth::user();

        if ($user && $user->is_suspended) {
            if ($request->expectsJson() || $request->is('api/*')) {
                if ($request->user()?->currentAccessToken()) {
                    $request->user()->currentAccessToken()->delete();
                }

                return response()->json([
                    'message' => 'Your account has been suspended. Please contact support.',
                ], 403);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('index')
                ->with('error', 'Your account has been suspended. Please contact support.');
        }

        return $next($request);
    }
}
