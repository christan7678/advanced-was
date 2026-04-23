@extends('layouts.app')

@section('content')
<div class="auth-card">

    {{-- Brand Header --}}
    <div class="auth-brand">
        <div class="auth-brand-icon">
            <svg viewBox="0 0 24 24" fill="white" width="22" height="22">
                <path d="M12 2a7 7 0 00-7 7v3H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2v-6a2 2 0 00-2-2h-1V9a7 7 0 00-7-7zm-5 10V9a5 5 0 0110 0v3H7z"/>
            </svg>
        </div>
        <h2 class="auth-title">Forgot your password?</h2>
        <p class="auth-subtitle">Enter your email to reset your password</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success" style="margin-bottom: 16px;">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <div class="auth-field">
            <label for="email" class="auth-label">Email address</label>
            <input
                type="email"
                id="email"
                name="email"
                class="auth-input @error('email') is-invalid @enderror"
                placeholder="you@example.com"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                autofocus
            >

            @error('email')
                <div class="text-danger" style="margin-top: 6px; font-size: 14px;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn-primary">
            Send Reset Link
        </button>
    </form>

    <p class="auth-footer-text">
        Remember your password?
        <a href="{{ route('login') }}" class="auth-link">Back to login</a>
    </p>

</div>
@endsection