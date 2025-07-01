<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'site_password_enabled' => SiteSetting::get('site_password_enabled', false),
            'site_password' => SiteSetting::get('site_password', ''),
            'site_password_message' => SiteSetting::get('site_password_message', 'This site is password protected. Please enter the password to continue.'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_password_enabled' => 'boolean',
            'site_password' => 'nullable|string|max:255',
            'site_password_message' => 'nullable|string|max:500',
        ]);

        // Update settings
        SiteSetting::set('site_password_enabled', $request->boolean('site_password_enabled'), 'boolean', 'Enable site-wide password protection');
        
        if ($request->filled('site_password')) {
            SiteSetting::set('site_password', $request->site_password, 'string', 'Site-wide password for visitor access');
        }
        
        if ($request->filled('site_password_message')) {
            SiteSetting::set('site_password_message', $request->site_password_message, 'string', 'Message displayed on password protection page');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
