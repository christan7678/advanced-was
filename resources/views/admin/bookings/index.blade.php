@extends('layouts.app')

@section('content')
<h2 class="section-title">Admin - Booking List</h2>

<div class="card table-card p-3">
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Event</th>
                    <th>Email</th>
                    <th>Quantity</th>
                    <th>Payment</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Lavender Lo</td>
                    <td>Music Festival 2026</td>
                    <td>lavender@example.com</td>
                    <td>2</td>
                    <td>Online Banking</td>
                    <td><span class="badge bg-success">Confirmed</span></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Alex Tan</td>
                    <td>Tech Conference 2026</td>
                    <td>alex@example.com</td>
                    <td>1</td>
                    <td>E-Wallet</td>
                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection