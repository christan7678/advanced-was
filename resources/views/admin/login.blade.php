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
        <form>

            <div class="admin-auth-field">
                <label>Email</label>
                <input type="email" placeholder="admin@email.com">
            </div>

            <div class="admin-auth-field">
                <label>Password</label>
                <input type="password" placeholder="Enter password">
            </div>

            <button type="button" class="admin-btn-primary"
                onclick="window.location.href='/admin/dashboard'">
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