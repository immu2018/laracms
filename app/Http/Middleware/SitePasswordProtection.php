<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SitePasswordProtection
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip if site password protection is disabled
        if (!SiteSetting::isSitePasswordEnabled()) {
            return $next($request);
        }

        // Skip if user is authenticated (any logged-in user bypasses site password)
        if (Auth::check()) {
            return $next($request);
        }

        // Skip if accessing admin routes
        if ($request->is('admin/*') || $request->is('login') || $request->is('register')) {
            return $next($request);
        }

        // Skip if accessing site password routes
        if ($request->is('site-password') || $request->is('site-password-check')) {
            return $next($request);
        }

        // Check if user has entered correct site password
        $sitePassword = SiteSetting::getSitePassword();
        $sessionPassword = session('site_password_verified');

        // Debug logging
        Log::info('Middleware check', [
            'url' => $request->url(),
            'site_password' => $sitePassword,
            'session_password' => $sessionPassword,
            'match' => $sessionPassword === $sitePassword,
            'auth_check' => Auth::check()
        ]);

        if (empty($sitePassword) || $sessionPassword === $sitePassword) {
            return $next($request);
        }

        // Store the current URL so we can redirect back after password entry
        if (!$request->is('site-password') && !$request->is('site-password-check')) {
            session(['url.intended' => $request->url()]);
        }

        // Redirect to site password form
        return redirect()->route('site-password.form');
    }
}
