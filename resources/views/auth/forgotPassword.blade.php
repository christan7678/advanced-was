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

    {{-- Form --}}
    <form action="#" method="GET" onsubmit="return false;">
        @csrf

        <div class="auth-field">
            <label for="email" class="auth-label">Email address</label>
            <input
                type="email"
                id="email"
                name="email"
                class="auth-input"
                placeholder="you@example.com"
                required
            >
        </div>

        <button type="submit" class="btn-primary">
            Send Reset Link
        </button>
    </form>

    <p class="auth-footer-text">
        Remember your password? 
        <a href="/login" class="auth-link">Back to login</a>
    </p>

</div>
@endsection