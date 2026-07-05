<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    /**
     * @param  string  $permission  Permission slug from config/permissions.php
     * @param  string  $partnerAccess  Pass "partner" to allow partners through (deal routes)
     */
    public function handle(Request $request, Closure $next, string $permission, string $partnerAccess = ''): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if ($partnerAccess === 'partner' && $user->isPartner()) {
            return $next($request);
        }

        if ($user->hasPermission($permission)) {
            return $next($request);
        }

        abort(403, 'You do not have permission to access this area.');
    }
}
