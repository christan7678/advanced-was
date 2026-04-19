<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>User</th>
                <th>Email</th>
                <th>Tickets</th>
                <th>Total Paid</th>
                <th>Booked On</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($bookings as $booking)
                @php
                    $event = $booking->event;
                    $user = $booking->user;
                    $statusVal = $booking->payment_status ?: 'pending';
                    $price = $event ? (float) $event->price : 0.0;
                    $total = $price * (int) $booking->number_of_seats;
                @endphp
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td><div class="td-title">{{ $user->name ?? '—' }}</div></td>
                    <td class="td-sub">{{ $user->email ?? '—' }}</td>
                    <td>{{ $booking->number_of_seats }}</td>
                    <td>RM {{ number_format($total, 2) }}</td>
                    <td>{{ $booking->created_at ? $booking->created_at->format('d M Y') : '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $statusVal }}">{{ ucfirst($statusVal) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="act-btn act-view">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="td-empty">No bookings found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
