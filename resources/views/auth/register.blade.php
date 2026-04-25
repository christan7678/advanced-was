@extends('layouts.app')

@section('content')
    <div class="auth-card">

        <div class="auth-brand">
            <div class="auth-brand-icon">
                <svg viewBox="0 0 24 24" fill="white" width="22" height="22">
                    <path
                        d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-7 3a3 3 0 110 6 3 3 0 010-6zm6 13H6v-.5c0-2.5 3.13-4.5 6-4.5s6 2 6 4.5V19z" />
                </svg>
            </div>
            <h2 class="auth-title">Create your account!</h2>
            <p class="auth-subtitle">Start booking events today</p>
        </div>

        <div class="auth-divider">
            <span class="auth-divider-line"></span>
            <span class="auth-divider-text">or</span>
            <span class="auth-divider-line"></span>
        </div>

        <form method="POST" action='{{ route("register") }}' aria-label="{{ __('Register') }}">
            @csrf
            {{-- Name --}}
            <div class="auth-field">
                <label for="name" class="auth-label">{{ __('Name') }}</label>

                {{-- Error handling for name field --}}
                {{-- need add ui design for error message --}}
                {{-- class="auth-error" + class="is-invalid" --}}
                <div class="auth-error">
                    <input type="text" name="name" id="name" class="auth-input @error('name') is-invalid @enderror"
                        placeholder="your name" value="{{ old('name') }}" required>


                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

            </div>


            {{-- Email --}}
            <div class="auth-field">
                <label for="email" class="auth-label">{{ __('Email address') }}</label>

                <div class="auth-error">
                    <input type="email" name="email" id="email" class="auth-input @error('email') is-invalid @enderror"
                        placeholder="you@example.com" required autocomplete="email" value="{{ old('email') }}">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>

            </div>

            {{-- Phone --}}
            <div class="auth-field">
                <label for="phone" class="auth-label">{{ __('Phone number') }}</label>
                <div class="auth-error">
                    <input type="tel" name="phone_number" id="phone_number"
                        class="auth-input @error('phone_number') is-invalid @enderror"
                        placeholder="0123456789"
                        required autocomplete="tel"
                        value="{{ old('phone_number') }}">

                    @error('phone_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>
            </div>

            {{-- Password --}}
            <div class="auth-field">
                <label for="password" class="auth-label">{{ __('Password') }}</label>

                <div class="auth-error">
                    <div class="auth-pw-wrap">
                        <input type="password" id="password" name="password"
                            class="auth-input @error('password') is-invalid @enderror" placeholder="Enter password" required
                            autocomplete="new-password" oninput="toggleEyeVisibility('password','togglePasswordBtn')">

                        <button type="button" id="togglePasswordBtn" class="auth-pw-toggle"
                            onclick="togglePassword('password', this)">

                            <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>

                            <svg class="eye-close" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" style="display:none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                                <path d="M14.12 14.12A3 3 0 0 1 9.88 9.88" />
                                <line x1="1" y1="1" x2="23" y2="23" />
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

            {{-- Confirm Password --}}
            <div class="auth-field">
                <label for="confirm_password" class="auth-label">{{ __('Confirm Password') }}</label>

                <div class="auth-error">
                    <div class="auth-pw-wrap">
                        <input type="password" id="confirm_password" name="password_confirmation"
                            class="auth-input @error('password_confirmation') is-invalid @enderror"
                            placeholder="Confirm password" required autocomplete="new-password"
                            oninput="toggleEyeVisibility('confirm_password','toggleConfirmBtn')">

                        <button type="button" id="toggleConfirmBtn" class="auth-pw-toggle"
                            onclick="togglePassword('confirm_password', this)">

                            <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>

                            <svg class="eye-close" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" style="display:none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                                <path d="M14.12 14.12A3 3 0 0 1 9.88 9.88" />
                                <line x1="1" y1="1" x2="23" y2="23" />
                            </svg>
                        </button>
                    </div>

                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-primary">
                {{ __('Create account') }}
            </button>

        </form>

        <p class="auth-footer-text">
            Already have an account?
            <a href="/login" class="auth-link">Sign in</a>
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
            toggleEyeVisibility('confirm_password', 'toggleConfirmBtn');
        });
    </script>
@endpush