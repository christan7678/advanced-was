@extends('layouts.app')

@section('content')
<div class="account-page">

    <section class="account-section">

        <div class="account-back">
            <a href="/profile" onclick="if(history.length > 1){ history.back(); return false; }" class="back-btn">
                🡰 Back
            </a>
        </div>
        
        <div class="account-section-label">Terms of Service</div>

        <div class="account-menu" style="padding:10px 6px;">
            <div style="max-width:700px;">

                <h2>Terms of Use</h2>

                <p>
                By using EventBook, you agree to comply with all applicable laws and regulations.
                Users are responsible for maintaining account security and ensuring accurate booking details.
                EventBook reserves the right to modify or cancel bookings when necessary.
                </p>

            </div>
        </div>

    </section>

</div>
@endsection