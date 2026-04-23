@extends('layouts.app')

@section('content')
<div class="account-page">

    <section class="account-section account-hero">
        <div class="account-section-label">ACCOUNT</div>

        <div class="account-top">
            <div class="account-user">
                <div class="account-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="account-user-info">
                    <h2>{{ Auth::user()->name ?? 'User Name' }}</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="account-signout-btn">
                    Sign Out
                </button>
            </form>
        </div>

        <div class="account-menu">
            <a href="{{ route('profile.detail') }}" class="account-menu-item">
                <div>
                    <h3>Account</h3>
                    <p>Manage your account information</p>
                </div>
                <span class="account-arrow">›</span>
            </a>

            <a href="{{ route('pages.support') }}" class="account-menu-item">
                <div>
                    <h3>Support</h3>
                    <p>Get help for booking and payment issues</p>
                </div>
                <span class="account-arrow">›</span>
            </a>
        </div>
    </section>

    <section class="account-section">
        <div class="account-section-label">BOOKINGS</div>

        <div class="account-menu">
            <a href="{{ route('profile.tickets') }}" class="account-menu-item">
                <div>
                    <h3>My Tickets</h3>
                    <p>View all booked event tickets</p>
                </div>
                <span class="account-arrow">›</span>
            </a>

            
            <a href="{{ route('profile.history') }}" class="account-menu-item">
                <div>
                    <h3>Purchase History</h3>
                    <p>View your booking and payment records</p>
                </div>
                <span class="account-arrow">›</span>
            </a>
        </div>
    </section>

    <section class="account-section">
        <div class="account-section-label">SECURITY</div>

        <div class="account-menu">
            <a href="{{ route('profile.password') }}" class="account-menu-item">
                <div>
                    <h3>Password</h3>
                    <p>Change your password</p>
                </div>
                <span class="account-arrow">›</span>
            </a>
        </div>
    </section>

    <section class="account-section">
        <div class="account-section-label">INFORMATION</div>

        <div class="account-menu">
            <a href="{{ route('pages.terms') }}" class="account-menu-item">
                <div>
                    <h3>Terms of Use</h3>
                    <p>Read the terms for using EventBook</p>
                </div>
                <span class="account-arrow">›</span>
            </a>

            <a href="{{ route('pages.privacy') }}" class="account-menu-item">
                <div>
                    <h3>Privacy Policy</h3>
                    <p>Read how your data is protected</p>
                </div>
                <span class="account-arrow">›</span>
            </a>
        </div>
    </section>

</div>
@endsection