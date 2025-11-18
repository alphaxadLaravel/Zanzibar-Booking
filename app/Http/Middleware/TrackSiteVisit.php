<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SiteVisit;

class TrackSiteVisit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track visits for non-admin routes and non-API routes
        if (!$request->is('admin/*') && !$request->is('api/*')) {
            try {
                $ipAddress = $request->ip();
                $userAgent = $request->userAgent();
                $sessionId = $request->session()->getId();
                
                // Track the visit
                SiteVisit::trackVisit($ipAddress, $userAgent, $sessionId);
            } catch (\Exception $e) {
                // Silently fail if tracking fails to not interrupt the request
                \Log::error('Site visit tracking failed: ' . $e->getMessage());
            }
        }
        
        return $next($request);
    }
}
