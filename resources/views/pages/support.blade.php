@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/profile/profile-detail.css') }}">
@endsection

@section('content')
<div class="profile-detail-page">

    <section class="profile-detail-section">

        <div class="profile-detail-header">
            <div>
                <div class="profile-detail-label">Support</div>
                <h1>Support Center</h1>
                <p>We’re here to help you</p>
            </div>

            <a href="{{ url()->previous() }}" class="profile-detail-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     fill="none" stroke="currentColor" stroke-width="3">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </a>
        </div>

        <div class="profile-info-card text-block">
            Need help? Contact our support team for booking or payment issues.<br><br>
            Email: support@eventbook.com<br>
            Response time: within 24 hours
        </div>

    </section>

</div>
@endsection