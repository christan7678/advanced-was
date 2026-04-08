@extends('layouts.app')

@section('content')
<div class="account-page">

    <section class="account-section">

        <div class="account-back">
            <a href="/profile" onclick="if(history.length > 1){ history.back(); return false; }" class="back-btn">
                🡰 Back
            </a>
        </div>

        <div class="account-section-label">Your Address</div>

        <div class="account-menu" style="padding:10px 6px;">
            <div style="max-width:700px;">

                <h2>Your Address</h2>

                <p>No address saved yet.</p>

            </div>
        </div>

    </section>

</div>
@endsection