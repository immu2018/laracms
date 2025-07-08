@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Site Settings</h2>
                <div class="text-muted mt-1">Manage site-wide configuration and security settings</div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 12l5 5l10 -10"></path>
                        </svg>
                    </div>
                    <div>{{ session('success') }}</div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Site Password Protection</h3>
                            <div class="card-subtitle">Control access to your entire website</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="site_password_enabled" 
                                                   value="1"
                                                   {{ $settings['site_password_enabled'] ? 'checked' : '' }}>
                                            <span class="form-check-label">Enable Site Password Protection</span>
                                        </label>
                                        <small class="form-hint">When enabled, visitors must enter a password to access your site. Logged-in admins bypass this protection.</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label required">Site Password</label>
                                        <input type="password" 
                                               class="form-control @error('site_password') is-invalid @enderror" 
                                               name="site_password" 
                                               value="{{ $settings['site_password'] }}"
                                               placeholder="Enter site password">
                                        @error('site_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-hint">Leave blank to keep current password unchanged.</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Password Page Message</label>
                                        <textarea class="form-control @error('site_password_message') is-invalid @enderror" 
                                                  name="site_password_message" 
                                                  rows="3"
                                                  placeholder="Message to show on password page">{{ $settings['site_password_message'] }}</textarea>
                                        @error('site_password_message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-hint">This message will be displayed to visitors on the password protection page.</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="site_login_required" 
                                                   value="1"
                                                   {{ $settings['site_login_required'] ? 'checked' : '' }}>
                                            <span class="form-check-label">Require Login to Access Site</span>
                                        </label>
                                        <small class="form-hint">When enabled, only logged-in users can access the site. Visitors will be redirected to login page.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                                Save Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">How It Works</h3>
                    </div>
                    <div class="card-body">
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Protection Level</div>
                                <div class="datagrid-content">Entire Website</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Admin Access</div>
                                <div class="datagrid-content">Always Bypassed</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Session Duration</div>
                                <div class="datagrid-content">Until Browser Close</div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <h4>Current Status:</h4>
                            @if($settings['site_password_enabled'])
                                <span class="badge bg-warning">🔒 Site Protected</span>
                            @else
                                <span class="badge bg-success">🌐 Site Open</span>
                            @endif
                        </div>
                        
                        @if($settings['site_password_enabled'])
                        <div class="mt-3">
                            <a href="{{ route('site-password.form') }}" 
                               class="btn btn-outline-primary btn-sm w-100" 
                               target="_blank">
                                Preview Password Page
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
