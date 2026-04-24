@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/profile/profile-detail.css') }}">
@endsection

@section('content')
<div class="profile-detail-page">

    <section class="profile-detail-section">

        <div class="profile-detail-header">
            <div>
                <div class="profile-detail-label">Refund</div>
                <h1>Refund Policy</h1>
                <p>Understand refund conditions and process</p>
            </div>

            <a href="{{ url()->previous() }}" class="profile-detail-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     fill="none" stroke="currentColor" stroke-width="3">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </a>
        </div>

        <div class="profile-info-card text-block">
            Refunds are subject to event organizer policies.<br><br>
            Requests must be made within the allowed timeframe.<br><br>
            Processing may take 5–7 working days.
        </div>

    </section>

</div>
@endsection