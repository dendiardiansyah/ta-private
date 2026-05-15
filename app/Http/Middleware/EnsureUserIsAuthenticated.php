<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAuthenticated
{
    /**
     * Handle an incoming request.
     * Ensure unauthenticated users can only access public routes (login, register, welcome).
     * All other routes require authentication.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow unauthenticated users to access specific routes
        $publicRoutes = ['login', 'register', 'welcome'];

        // If user is authenticated, allow the request
        if ($request->user()) {
            return $next($request);
        }

        // If the route is public, allow unauthenticated access
        foreach ($publicRoutes as $route) {
            if ($request->routeIs($route) || $request->path() === '/') {
                return $next($request);
            }
        }

        // Unauthenticated user trying to access protected route
        // Redirect to login
        return redirect()->route('login');
    }
}
