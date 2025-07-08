<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireLogin
{
    public function handle(Request $request, Closure $next)
    {
        // Skip if site login requirement is disabled
        if (!SiteSetting::get('require_login_enabled', false)) {
            return $next($request);
        }

        // Skip if user is already authenticated
        if (Auth::check()) {
            return $next($request);
        }

        // Skip login/register routes
        if ($request->is('login') || $request->is('register') || $request->is('password/*')) {
            return $next($request);
        }

        // Skip site password routes
        if ($request->is('site-password') || $request->is('site-password-check')) {
            return $next($request);
        }

        // Redirect to login
        return redirect()->route('login');
    }
}
