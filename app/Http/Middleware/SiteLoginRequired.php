<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteLoginRequired
{
    public function handle(Request $request, Closure $next)
    {
        // Skip if site login requirement is disabled
        if (!SiteSetting::get('site_login_required', false)) {
            return $next($request);
        }

        // Skip if user is authenticated
        if (Auth::check()) {
            return $next($request);
        }

        // Skip auth routes
        if ($request->is('login') || $request->is('register') || $request->is('password/*') || $request->is('admin/*')) {
            return $next($request);
        }

        // Skip site password routes
        if ($request->is('site-password') || $request->is('site-password-check')) {
            return $next($request);
        }

        // Store intended URL and redirect to login
        if (!$request->is('login') && !$request->is('register')) {
            session(['url.intended' => $request->url()]);
        }

        return redirect()->route('login');
    }
}
