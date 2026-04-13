@extends('layouts.app')

@section('content')
<div class="account-page">

    <section class="account-section">

        <div class="account-back">
            <a href="/profile" onclick="if(history.length > 1){ history.back(); return false; }" class="back-btn">
                🡰 Back
            </a>
        </div>
        
        <div class="account-section-label">Privacy Policy</div>

        <div class="account-menu" style="padding:10px 6px;">
            <div style="max-width:700px;">

                <h2>Privacy Policy</h2>

                <p>
                We collect your basic information such as name, email, and phone number
                to provide booking services. Your data will not be shared with third parties
                without consent except where required by law.
                </p>

            </div>
        </div>

    </section>

</div>
@endsection