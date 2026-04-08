@extends('layouts.app')

@section('content')
<div class="account-page">

    <section class="account-section">

        <div class="account-back">
            <a href="/profile" onclick="if(history.length > 1){ history.back(); return false; }" class="back-btn">
                🡰 Back
            </a>
        </div>
        
        <div class="account-section-label">Account Information</div>

        <div class="account-menu" style="padding:10px 6px;">
            <div style="max-width:700px;">

            <h2>Account Information</h2>

            <p>Name: Wad Lavender Lo</p>
            <p>Email: wad2602@example.com</p>
            <p>Phone: +60 12-345 6789</p>

            </div>
        </div>

    </section>

</div>
@endsection