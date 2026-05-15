<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     * If user is authenticated and trying to access login/register, redirect to appropriate dashboard.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is not authenticated, allow the request
        if (!$request->user()) {
            return $next($request);
        }

        // User is authenticated. If they're trying to access login or register, redirect them
        if ($request->routeIs('login') || $request->routeIs('register')) {
            $user = $request->user();

            // Redirect to appropriate dashboard based on role
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->hasRole('pelaku_usaha')) {
                return redirect()->route('pelaku_usaha.dashboard');
            }

            if ($user->hasRole('petugas')) {
                return redirect()->route('petugas.index');
            }

            // Default redirect for nasabah/user
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
