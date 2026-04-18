@extends('admin.auth-layout')

@section('content')
<div class="admin-auth-wrapper">

    <div class="admin-auth-card">

        {{-- Header --}}
        <div class="admin-auth-header">
            <div class="admin-auth-icon">EB</div>
            <h2>Admin Login</h2>
            <p>Access admin dashboard</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="admin-auth-field">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@email.com" required>
                @error('email')
                    <div style="color:#dc2626;font-size:12px;margin-top:6px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-auth-field">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
                @error('password')
                    <div style="color:#dc2626;font-size:12px;margin-top:6px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-auth-field" style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" style="margin:0;">Remember me</label>
            </div>

            <button type="submit" class="admin-btn-primary">
                Login
            </button>

        </form>

        {{-- Back to user --}}
        <div class="admin-auth-footer">
            <a href="/login">← Back to User Login</a>
        </div>

    </div>

</div>
@endsection