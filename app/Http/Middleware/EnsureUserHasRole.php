<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Usage: ->middleware('role:admin') or ->middleware('role:admin,petugas')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        $roles = array_values(array_filter(array_map('trim', $roles)));

        if ($roles === []) {
            return $next($request);
        }

        if (!method_exists($user, 'hasAnyRole') || !$user->hasAnyRole($roles)) {
            abort(403);
        }

        return $next($request);
    }
}
