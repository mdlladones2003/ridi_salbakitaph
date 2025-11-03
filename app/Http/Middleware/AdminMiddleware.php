<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user has admin role using the helper method
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access. Admin privileges required.');
        }

        return $next($request);
    }
}
