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

            <a href="{{ route('profile.index') }}" class="profile-detail-back">
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
                    <div class="auth-pw-wrap" >
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            value="{{ old('current_password') }}"
                            placeholder="Enter your current password"
                            oninput="toggleEyeVisibility('password', 'togglePasswordBtn')"
                            required
                        >
                        <button
                            type="button"
                            class="auth-pw-toggle"
                            onclick="togglePassword(this)"
                            aria-label="Show password"
                        >
                            <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>

                            <svg class="eye-close" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <path d="M14.12 14.12A3 3 0 0 1 9.88 9.88"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <div class="password-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="password-field">
                    <label>New Password</label>
                    <div class="auth-pw-wrap" >
                        <input
                            type="password"
                            id="password"
                            name="password"
                            value="{{ old('password') }}"
                            placeholder="Enter your new password"
                            oninput="toggleEyeVisibility('password', 'togglePasswordBtn')"
                            required
                        >
                        <button
                            type="button"
                            class="auth-pw-toggle"
                            onclick="togglePassword(this)"
                            aria-label="Show password"
                        >
                            <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>

                            <svg class="eye-close" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <path d="M14.12 14.12A3 3 0 0 1 9.88 9.88"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    <div class="password-hint">
                        Password must be at least 8 characters long.
                    </div>
                    @error('password')
                        <div class="password-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="password-field">
                    <label>Confirm Password</label>
                    <div class="auth-pw-wrap" >
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Re-enter your new password"
                            oninput="toggleEyeVisibility('password', 'togglePasswordBtn')"
                            required
                        >
                        <button
                            type="button"
                            class="auth-pw-toggle"
                            onclick="togglePassword(this)"
                            aria-label="Show password"
                        >
                            <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>

                            <svg class="eye-close" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <path d="M14.12 14.12A3 3 0 0 1 9.88 9.88"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
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

@push('scripts')
    <script>
        function togglePassword(btn) {
            const input = btn.previousElementSibling;
            const eyeOpen = btn.querySelector('.eye-open');
            const eyeClose = btn.querySelector('.eye-close');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.style.display = 'block';
                eyeClose.style.display = 'none';
            } else {
                input.type = 'password';
                eyeOpen.style.display = 'none';
                eyeClose.style.display = 'block';
            }
        }

        function toggleEyeVisibility(inputId, buttonId) {
            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);
            const eyeOpen = button.querySelector('.eye-open');
            const eyeClose = button.querySelector('.eye-close');

            if (input.value.length > 0) {
                button.classList.add('show-eye');

                if (input.type === 'password') {
                    eyeOpen.style.display = 'none';
                    eyeClose.style.display = 'block';
                } else {
                    eyeOpen.style.display = 'block';
                    eyeClose.style.display = 'none';
                }
            } else {
                button.classList.remove('show-eye');
                input.type = 'password';
                eyeOpen.style.display = 'none';
                eyeClose.style.display = 'block';
                button.setAttribute('aria-label', 'Show password');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            toggleEyeVisibility('password', 'togglePasswordBtn');
        });
    </script>
@endpush