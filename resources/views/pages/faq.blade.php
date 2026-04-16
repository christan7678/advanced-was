@extends('layouts.app')

@section('content')
<div class="account-page">

    <section class="account-section">

        <div class="account-back">
            <a href="/profile" onclick="if(history.length > 1){ history.back(); return false; }" class="back-btn">
                🡰 Back
            </a>
        </div>

        <div class="account-section-label">Frequently Asked Questions</div>

        <div class="account-menu" style="padding:10px 6px;">
            <div style="max-width:700px;">

            <h2>Frequently Asked Questions</h2>

            <p><strong>Q: How do I book a ticket?</strong><br>
            A: Select an event, choose a date, and proceed to payment.</p>

            <p><strong>Q: Can I cancel my booking?</strong><br>
            A: Yes, depending on the event policy.</p>

            <p><strong>Q: How do I contact support?</strong><br>
            A: Use the Support Center page.</p>

            </div>
        </div>

    </section>

</div>
@endsection