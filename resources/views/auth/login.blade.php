@extends('layouts.app')

@section('content')


<div class="auth-card">

    {{-- Brand Header --}}
    <div class="auth-brand">
        <div class="auth-brand-icon">
            <svg viewBox="0 0 24 24" fill="white" width="22" height="22">
                <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-7 3a3 3 0 110 6 3 3 0 010-6zm6 13H6v-.5c0-2.5 3.13-4.5 6-4.5s6 2 6 4.5V19z"/>
            </svg>
        </div>
        <h2 class="auth-title">Welcome back!</h2>
        <p class="auth-subtitle">Sign in to manage your bookings</p>
    </div>


    <div class="auth-divider">
        <span class="auth-divider-line"></span>
        <span class="auth-divider-text">or</span>
        <span class="auth-divider-line"></span>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    {{-- Login Form --}}
     <form method="POST" action='{{ route("login") }}' aria-label="{{ __('Login') }}">
        @csrf

        {{-- Email --}}
        <div class="auth-field">
            <label for="email" class="auth-label">Email address</label>
            <div class="auth-error">
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="auth-input @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="wad2602@example.com"
                    autocomplete="off">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror   
            </div>
        </div>


        {{-- Password --}}
        <div class="auth-field">
            <div class="auth-label-row">
                <label for="password" class="auth-label">Password</label>
            </div>

            <div class="auth-error">
                <div class="auth-pw-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="auth-input @error('password') is-invalid @enderror"
                        placeholder="Enter your password"
                        autocomplete="off"
                        oninput="toggleEyeVisibility('password', 'togglePasswordBtn')"
                    >

                    <button
                        type="button"
                        id="togglePasswordBtn"
                        class="auth-pw-toggle"
                        onclick="togglePassword('password', this)"
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

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Remember Me & Forgot Password --}}
        <div class="auth-row">
            <div class="auth-checkbox-row">
                <input type="checkbox" id="remember" name="remember" class="auth-checkbox">
                <label for="remember" class="auth-checkbox-label">Keep me signed in</label>
            </div>

            <a href="/forgotPassword" class="auth-link-sm">Forgot password?</a>
        </div>

        <button type="submit" class="btn-primary">
            Sign in
        </button>    
    </form>

    <p class="auth-footer-text">
        No account?
        <a href="/register" class="auth-link">Create one free</a>
    </p>

</div>
@endsection

@push('scripts')
    <script>
        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const eyeOpen = btn.querySelector('.eye-open');
            const eyeClose = btn.querySelector('.eye-close');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.style.display = 'block';
                eyeClose.style.display = 'none';
                btn.setAttribute('aria-label', 'Hide password');
            } else {
                input.type = 'password';
                eyeOpen.style.display = 'none';
                eyeClose.style.display = 'block';
                btn.setAttribute('aria-label', 'Show password');
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