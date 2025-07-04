<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if site-wide authentication is enabled
        $authRequired = SiteSetting::get('require_login', false);
        
        if (!$authRequired) {
            return $next($request);
        }

        // Skip if user is authenticated
        if (Auth::check()) {
            return $next($request);
        }

        // Skip for auth routes (login, register, password reset)
        if ($request->is('login') || 
            $request->is('register') || 
            $request->is('password/*') || 
            $request->is('forgot-password') || 
            $request->is('reset-password/*') ||
            $request->is('verify-email') ||
            $request->is('email/verify/*')) {
            return $next($request);
        }

        // Skip for API routes if needed
        if ($request->is('api/*')) {
            return $next($request);
        }

        // Store intended URL and redirect to login
        return redirect()->guest(route('login'));
    }
}
