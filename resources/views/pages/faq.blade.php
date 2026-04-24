@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/profile/profile-detail.css') }}">
@endsection

@section('content')
<div class="profile-detail-page">

    <section class="profile-detail-section">

        <div class="profile-detail-header">
            <div>
                <div class="profile-detail-label">FAQ</div>
                <h1>Frequently Asked Questions</h1>
                <p>Quick answers to common questions</p>
            </div>

            <a href="{{ url()->previous() }}" class="profile-detail-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     fill="none" stroke="currentColor" stroke-width="3">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </a>
        </div>

        <div class="profile-info-card text-block">

            <p><strong>How do I book a ticket?</strong><br>
            Select event → choose seats → proceed payment.</p>

            <br>

            <p><strong>Can I cancel booking?</strong><br>
            Yes, depending on event policy.</p>

            <br>

            <p><strong>How to contact support?</strong><br>
            Use the Support page.</p>

        </div>

    </section>

</div>
@endsection