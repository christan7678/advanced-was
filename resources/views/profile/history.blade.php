@extends('layouts.app')

@section('content')
<div class="account-page">

    <section class="account-section">

        <div class="account-back">
            <a href="/profile" onclick="if(history.length > 1){ history.back(); return false; }" class="back-btn">
                🡰 Back
            </a>
        </div>

        <div class="account-section-label">Purchase History</div>

        <div class="account-menu" style="padding:10px 6px;">
            <div style="max-width:700px;">

                <h2>Purchase History</h2>

                <p>No purchase history available.</p>

            </div>
        </div>

    </section>

</div>
@endsection