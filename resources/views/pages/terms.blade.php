@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/profile/profile-detail.css') }}">
@endsection

@section('content')
<div class="profile-detail-page">

    <section class="profile-detail-section">

        <div class="profile-detail-header">
            <div>
                <div class="profile-detail-label">Terms</div>
                <h1>Terms of Service</h1>
                <p>Rules and responsibilities when using EventBook</p>
            </div>

            <a href="{{ url()->previous() }}" class="profile-detail-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     fill="none" stroke="currentColor" stroke-width="3">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </a>
        </div>

        <div class="profile-info-card text-block">
            By using EventBook, you agree to comply with applicable laws.<br><br>
            You are responsible for your account security and booking accuracy.<br><br>
            EventBook may modify or cancel bookings when necessary.
        </div>

    </section>

</div>
@endsection