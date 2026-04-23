<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin – @yield('title', 'EventBook')</title>

    <link rel="stylesheet" href="{{ asset('css/admin/admin-auth.css') }}">    
    <link rel="stylesheet" href="{{ asset('css/admin/admin-bookings.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-categories.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-events.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-users.css') }}">    

    @yield('styles')
</head>
<body>

<div class="admin-shell">

    {{-- ================= SIDEBAR ================= --}}
    <aside class="admin-sidebar">

        <div class="sb-logo">EventBook</div>

        <div class="sb-section-label">Main</div>

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="sb-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"/>
                <rect x="14" y="3" width="7" height="7"/>
                <rect x="3" y="14" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/>
            </svg>
            Dashboard
        </a>

        {{-- Events --}}
        <a href="{{ route('admin.events.index') }}"
           class="sb-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <path d="M16 2v4M8 2v4M3 10h18"/>
            </svg>
            Events
        </a>

        {{-- Bookings --}}
        <a href="{{ route('admin.bookings.index') }}"
           class="sb-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M2 7h20M2 12h20M2 17h20"/>
            </svg>
            Bookings
        </a>

        <div class="sb-section-label">Manage</div>

        {{-- Users --}}
        <a href="{{ route('admin.users.index') }}"
           class="sb-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
            </svg>
            Users
        </a>

        @if(auth()->check() && auth()->user()->role === 'super_admin')
            @if(Route::has('admin.admins.index'))
                <a href="{{ route('admin.admins.index') }}"
                class="sb-item {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    </svg>
                    Admins
                </a>
            @endif
        @endif

        {{-- Categories --}}
        <a href="{{ route('admin.categories.index') }}"
           class="sb-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.07 4.93A10 10 0 1 1 4.93 19.07"/>
            </svg>
            Categories
        </a>

        {{-- Bottom user --}}
        <div class="sb-bottom">
            <div class="sb-user">
                <div class="sb-avatar">AD</div>
                <div>
                    <div class="sb-user-name">Admin</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}"
                  onsubmit="return confirm('Are you sure you want to sign out?')">
                @csrf
                <button type="submit" class="sb-logout" style="background:none;border:0;padding:0;cursor:pointer;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Sign Out
                </button>
            </form>
        </div>

    </aside>

    {{-- ================= MAIN ================= --}}
    <div class="admin-content">

        {{-- Topbar --}}
        <div class="admin-topbar">
            <div class="topbar-title">@yield('page-title')</div>
            <div class="topbar-right">@yield('topbar-actions')</div>
        </div>

        {{-- Page --}}
        <div class="admin-page">
            @yield('content')
        </div>

    </div>

</div>

@yield('scripts')

</body>
</html>
