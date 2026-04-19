@extends('layouts.app')

@section('content')
<div class="account-page">

    <section class="account-section">

        <div class="account-back">
            <a href="/profile" onclick="if(history.length > 1){ history.back(); return false; }" class="back-btn">
                🡰 Back
            </a>
        </div>
        
        <div class="account-section-label">Account Information</div>

        <div class="account-menu" style="padding:10px 6px;">
            <div style="max-width:700px;">

            <h2>Account Information</h2>

            <div style="display:flex; align-items:center; gap:14px; margin:14px 0;">
                <div class="account-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:18px; font-weight:700; color:#111827;">{{ Auth::user()->name ?? 'User Name' }}</div>
                    <div style="font-size:14px; color:#6b7280;">{{ Auth::user()->email ?? 'Email' }}</div>
                </div>
            </div>

            </div>
        </div>

    </section>

</div>
@endsection