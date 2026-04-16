@extends('layouts.app')

@section('content')
<div class="account-page">

    <section class="account-section">

        <div class="account-back">
            <a href="/profile" onclick="if(history.length > 1){ history.back(); return false; }" class="back-btn">
                🡰 Back
            </a>
        </div>
        
        <div class="account-section-label">Refund Policy</div>

        <div class="account-menu" style="padding:10px 6px;">
            <div style="max-width:700px;">

            <h2>Refund Policy</h2>

            <p>
            Refunds are subject to event organizer policies.
            Requests must be made within the allowed timeframe.
            Processing time may take 5–7 working days.
            </p>

            </div>
        </div>

    </section>

</div>
@endsection