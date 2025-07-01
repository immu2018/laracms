{{-- Site Header with Navigation and Logout --}}
<header class="bg-white shadow p-6 flex items-center justify-between">
    <div class="text-2xl font-extrabold text-blue-700">Modern Theme</div>
    <div class="flex items-center space-x-4">
        {{-- Dynamic Menu --}}
        @php
            $menuItems = get_menu_items('header');
        @endphp
        @include('themes.modern.partials.menu', ['items' => $menuItems])
        
        {{-- Site Password Logout --}}
        @if(site_password_enabled() && is_site_password_verified() && !auth()->check())
            <form method="POST" action="{{ route('site-password.logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium px-3 py-1 border border-red-300 rounded hover:bg-red-50">
                    🔓 Logout
                </button>
            </form>
        @endif
    </div>
</header>
