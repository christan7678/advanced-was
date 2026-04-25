@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/profile/profile-detail.css') }}">
@endsection

@section('content')
<div class="profile-detail-page">
    <section class="profile-detail-section">

        <div class="profile-detail-header">
            <div>
                <div class="profile-detail-label">Security</div>
                <h1>Change Password</h1>
                <p>Update your account password securely.</p>
            </div>

            <a href="{{ url()->previous() }}" class="profile-detail-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </a>
        </div>

        <div class="password-card">

            @if(session('success'))
                <div class="password-alert success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="password-alert error">
                    <strong>Please fix the following errors:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf

                <div class="password-field">
                    <label>Current Password</label>
                    <input
                        type="password"
                        name="current_password"
                        value="{{ old('current_password') }}"
                        placeholder="Enter your current password"
                        required
                    >
                    @error('current_password')
                        <div class="password-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="password-field">
                    <label>New Password</label>
                    <input
                        type="password"
                        name="password"
                        value="{{ old('password') }}"
                        placeholder="Enter your new password"
                        required
                    >
                    <div class="password-hint">
                        Password must be at least 8 characters long.
                    </div>
                    @error('password')
                        <div class="password-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="password-field">
                    <label>Confirm Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="Re-enter your new password"
                        required
                    >
                    @error('password_confirmation')
                        <div class="password-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="password-submit-btn">
                    Update Password
                </button>
            </form>

            <div class="password-tips">
                <strong>Security Tips</strong>
                <ul>
                    <li>Use a strong password with letters, numbers, and symbols.</li>
                    <li>Never share your password with anyone.</li>
                    <li>Change your password regularly for better security.</li>
                    <li>If you suspect unauthorized access, change it immediately.</li>
                </ul>
            </div>

        </div>

    </section>
</div>
@endsection