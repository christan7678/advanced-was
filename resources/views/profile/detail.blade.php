@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/profile/profile-detail.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="profile-detail-page">
    <section class="profile-detail-section">

        <div class="profile-detail-header">
            <div>
                <div class="profile-detail-label">Account Information</div>
                <h1>Account Information</h1>
                <p>View your personal account details.</p>
            </div>

            <a href="{{ route('profile.index') }}" class="profile-detail-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </a>
        </div>

        <div class="profile-info-card">
            <div class="profile-avatar">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>

            <div class="profile-info-content">
                <div class="profile-info-label">Full Name</div>
                <div class="profile-info-value">{{ Auth::user()->name ?? 'User Name' }}</div>

                <div class="profile-info-label">Email Address</div>
                <div class="profile-info-value muted">{{ Auth::user()->email ?? 'Email' }}</div>

                <div class="profile-info-label">Phone number</div>
                <div class="profile-info-value muted">{{ Auth::user()->phone_number ?? 'Phone Number' }}</div>
            </div>
        </div>

    </section>
</div>
@endsection