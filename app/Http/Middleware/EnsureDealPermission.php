<?php

namespace App\Http\Middleware;

use App\Support\DealPermissions;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDealPermission
{
    /**
     * @param  string  $action  view|create|edit|delete
     * @param  string  ...$args  Optional "partner" bypass, or fixed deal type (hotel, activity, ...)
     */
    public function handle(Request $request, Closure $next, string $action, ...$args): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $partnerBypass = in_array('partner', $args, true);
        if ($partnerBypass && $user->isPartner()) {
            return $next($request);
        }

        $fixedType = collect($args)->first(fn (string $arg) => $arg !== 'partner');
        $dealType = DealPermissions::normalizeType(
            $fixedType ?: ($request->route('dealType') ?? $request->route('type'))
        );

        if (!$dealType) {
            abort(403, 'You do not have permission to access this deal area.');
        }

        if ($user->hasPermission(DealPermissions::slug($dealType, $action))) {
            return $next($request);
        }

        abort(403, 'You do not have permission to access this deal area.');
    }
}
