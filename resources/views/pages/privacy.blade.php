@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/profile/profile-detail.css') }}">
@endsection

@section('content')
<div class="profile-detail-page">

    <section class="profile-detail-section">

        <div class="profile-detail-header">
            <div>
                <div class="profile-detail-label">Privacy</div>
                <h1>Privacy Policy</h1>
                <p>How your data is handled and protected</p>
            </div>

            <a href="{{ url()->previous() }}" class="profile-detail-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     fill="none" stroke="currentColor" stroke-width="3">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </a>
        </div>

        <div class="profile-info-card text-block">
            We collect basic user data such as name and email to provide services.<br><br>
            Your data will never be shared without consent except when required by law.
        </div>

    </section>

</div>
@endsection