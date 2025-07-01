<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SitePasswordController extends Controller
{
    /**
     * Show the site password form
     */
    public function showForm()
    {
        return view('site-password', [
            'message' => SiteSetting::getSitePasswordMessage()
        ]);
    }

    /**
     * Handle site password submission
     */
    public function checkPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $sitePassword = SiteSetting::getSitePassword();
        
        // Debug logging
        Log::info('Password check', [
            'entered_password' => $request->password,
            'site_password' => $sitePassword,
            'match' => $request->password === $sitePassword
        ]);
        
        if ($request->password === $sitePassword) {
            // Store password in session with explicit save
            session(['site_password_verified' => $sitePassword]);
            session()->save(); // Force session save
            
            // Debug session
            Log::info('Session after save', [
                'session_password' => session('site_password_verified'),
                'all_session' => session()->all()
            ]);
            
            // Direct redirect to homepage (frontpage of active theme)
            return redirect('/')->with('success', 'Access granted!');
        }

        return back()->withErrors([
            'password' => 'The password is incorrect.'
        ])->withInput();
    }

    /**
     * Logout from site password (clear session)
     */
    public function logout()
    {
        session()->forget('site_password_verified');
        session()->save();
        
        return redirect()->route('site-password.form')
            ->with('success', 'You have been logged out.');
    }
}
