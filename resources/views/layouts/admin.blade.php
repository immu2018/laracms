{{--
    Admin Layout using Tabler Dashboard Template
    Place Tabler assets in public/vendor/tabler/
    Use @section('title') and @section('content') in child views
--}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>@yield('title', 'Admin Dashboard')</title>
    <!-- Tabler CSS files -->
    <link href="{{ asset('vendor/tabler/dist/css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tabler/dist/css/tabler-flags.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tabler/dist/css/tabler-socials.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tabler/dist/css/tabler-payments.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tabler/dist/css/tabler-vendors.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tabler/dist/css/tabler-marketing.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tabler/dist/css/demo.min.css') }}" rel="stylesheet"/>
    <style>@import url('https://rsms.me/inter/inter.css');</style>
    @stack('head')
</head>
<body>
<script src="{{ asset('vendor/tabler/dist/js/demo-theme.min.js') }}"></script>
<div class="page">
    {{-- Sidebar --}}
    {{-- Copy sidebar HTML from Tabler and replace static links with Blade routes as needed --}}
    @include('admin.partials.sidebar')
    {{-- Navbar --}}
    @include('admin.partials.navbar')
    <div class="page-wrapper">
        {{-- Page header (optional, can be yielded or included) --}}
        @yield('page-header')
        {{-- Main content --}}
        <div class="page-body">
            <div class="container-xl">
                @yield('content')
            </div>
        </div>
        {{-- Footer --}}
        @include('admin.partials.footer')
    </div>
</div>
<!-- Tabler JS and vendor scripts -->
<script src="{{ asset('vendor/tabler/dist/libs/apexcharts/dist/apexcharts.min.js') }}" defer></script>
<script src="{{ asset('vendor/tabler/dist/libs/jsvectormap/dist/jsvectormap.min.js') }}" defer></script>
<script src="{{ asset('vendor/tabler/dist/libs/jsvectormap/dist/maps/world.js') }}" defer></script>
<script src="{{ asset('vendor/tabler/dist/libs/jsvectormap/dist/maps/world-merc.js') }}" defer></script>
<script src="{{ asset('vendor/tabler/dist/js/tabler.min.js') }}" defer></script>
<script src="{{ asset('vendor/tabler/dist/js/demo.min.js') }}" defer></script>
@stack('scripts')
</body>
</html>
