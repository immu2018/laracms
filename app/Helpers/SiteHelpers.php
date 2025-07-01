<?php

if (!function_exists('is_site_password_verified')) {
    /**
     * Check if visitor has entered site password
     */
    function is_site_password_verified(): bool
    {
        $sitePassword = \App\Models\SiteSetting::getSitePassword();
        $sessionPassword = session('site_password_verified');
        
        return !empty($sitePassword) && $sessionPassword === $sitePassword;
    }
}

if (!function_exists('site_password_enabled')) {
    /**
     * Check if site password protection is enabled
     */
    function site_password_enabled(): bool
    {
        return \App\Models\SiteSetting::isSitePasswordEnabled();
    }
}
