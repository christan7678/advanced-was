@extends('layouts.app')

@section('content')
<div class="account-page">

    <section class="account-section">

        <div class="account-back">
            <a href="/profile" onclick="if(history.length > 1){ history.back(); return false; }" class="back-btn">
                🡰 Back
            </a>
        </div>
        
        <div class="account-section-label">Support Center</div>

        <div class="account-menu" style="padding:10px 6px;">
            <div style="max-width:700px;">

            <h2>Support Center</h2>

            <p>
            Need help? Our support team is available to assist you with booking issues,
            payment problems, and account inquiries.
            </p>

            <p>Email: support@eventbook.com</p>
            <p>Response time: within 24 hours</p>

            </div>
        </div>

    </section>

</div>
@endsection