@extends('layouts.app')

@section('content')
<div class="account-page">

    <section class="account-section">

        <div class="account-back">
            <a href="/profile" onclick="if(history.length > 1){ history.back(); return false; }" class="back-btn">
                🡰 Back
            </a>
        </div>

        <div class="account-section-label">Contact Us</div>

        <div class="account-menu" style="padding:10px 6px;">
            <div style="max-width:700px;">

            <h2>Contact Us</h2>

            <p>Company: EventBook Sdn. Bhd.</p>
            <p>Email: contact@eventbook.com</p>
            <p>Phone: +60 12-345 6789</p>
            <p>Location: Kuala Lumpur, Malaysia</p>

            </div>
        </div>

    </section>

</div>
@endsection