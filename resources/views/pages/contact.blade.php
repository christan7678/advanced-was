@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/profile/profile-detail.css') }}">
@endsection

@section('content')
<div class="profile-detail-page">

    <section class="profile-detail-section">

        <div class="profile-detail-header">
            <div>
                <div class="profile-detail-label">Contact</div>
                <h1>Contact Us</h1>
                <p>Reach out for booking or payment support</p>
            </div>

            <a href="{{ url()->previous() }}" class="profile-detail-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </a>
        </div>

        <div class="profile-info-card">
            <div class="info-list">
                <div class="info-row">
                    <span>Company</span>
                    <strong>EventBook Sdn. Bhd.</strong>
                </div>

                <div class="info-row">
                    <span>Email</span>
                    <strong>contact@eventbook.com</strong>
                </div>

                <div class="info-row">
                    <span>Phone</span>
                    <strong>+60 12-345 6789</strong>
                </div>

                <div class="info-row">
                    <span>Location</span>
                    <strong>Kuala Lumpur, Malaysia</strong>
                </div>
            </div>
        </div>

    </section>

</div>
@endsection