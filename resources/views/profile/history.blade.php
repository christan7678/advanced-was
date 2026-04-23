@extends('layouts.app')

@section('content')
    @php
        $query = $user->bookings()->with('event');

        $status = request('status');
        if(request('status')) {
            $query->where('payment_status', request('status'));
        }
        $bookings = $query->orderByDesc('created_at')->get();

        $purchaseCount = $user->bookings()->where('payment_status', 'completed')->count();
        $cancelCount = $user->bookings()->where('payment_status', 'cancelled')->count();
        $refundCount = $user->bookings()->where('payment_status', 'refunded')->count();
        
        if($status == 'completed'){
            $mainTitle = 'Total Purchased';
            $mainAmount = $bookings->sum('total_amount');
        }
        elseif($status == 'refunded'){
            $mainTitle = 'Total Refunded';
            $mainAmount = $bookings->sum('total_amount');
        }
        elseif($status == 'cancelled'){
            $mainTitle = 'Total Cancelled';
            $mainAmount = $bookings->sum('total_amount');
        }
        elseif($status == 'pending'){
            $mainTitle = 'Total Pending';
            $mainAmount =$bookings->sum('total_amount');
        }
        else {
            $mainTitle = 'Total Spent';
            $mainAmount = $bookings->where('payment_status', 'completed')->sum('total_amount');
        }
    @endphp

    <div class="account-page">
        <section class="account-section">
            <div class="account-back">
                <a href="{{ route('profile.index') }}" class="back-btn">
                    🡰 Back
                </a>
            </div>

            <div class="account-section-label">Purchase History</div>

            <div class="account-menu" style="padding: 20px;">
                <div style="max-width: 900px;">
                    <!-- Summary Stats -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 30px;">
                        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #059669;">{{ $purchaseCount }}</div>
                            <div style="font-size: 12px; color: #6b7280;">Purchase</div>
                        </div>
                        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #dc2626;">{{ $cancelCount }}</div>
                            <div style="font-size: 12px; color: #6b7280;">Cancelled</div>
                        </div>
                        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #2563eb;">{{ $refundCount }}</div>
                            <div style="font-size: 12px; color: #6b7280;">Refunded</div>
                        </div>
                        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #7c3aed;">
                                    RM{{ number_format($mainAmount, 2) }}
                            </div>
                            <div style="font-size: 12px; color: #6b7280;">{{ $mainTitle }}</div>
                        </div>
                    </div>

                    
                    <!-- Purchase List -->
                    <div>
                        <form method="GET" style="margin-bottom: 15px;">
                            <select name="status" onchange="this.form.submit()"
                                style="padding:6px 10px; border-radius:6px; border:1px solid #d1d5db;">
                                
                                <option value="">All Status</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </form>
                        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 15px; color: #1f2937;">
                            💳 All Purchases
                        </h3>

                        @if($bookings->count() > 0)
                            <div style="display: grid; gap: 12px;">
                                @foreach($bookings as $booking)
                                    @php
                                        $event = $booking->event;

                                        if ($booking->payment_status === 'completed') {
                                            $statusColor = '#059669';
                                            $statusLabel = 'Purchased';
                                            $amountColor = '#1f2937';
                                            $amountPrefix = 'RM';
                                        } elseif ($booking->payment_status === 'refunded') {
                                            $statusColor = '#2563eb';
                                            $statusLabel = 'Refunded';
                                            $amountColor = '#2563eb';
                                            $amountPrefix = 'RM';
                                        } elseif ($booking->payment_status === 'cancelled') {
                                            $statusColor = '#dc2626';
                                            $statusLabel = 'Cancelled';
                                            $amountColor = '#dc2626';
                                            $amountPrefix = 'RM';
                                        } else {
                                            $statusColor = '#92400e';
                                            $statusLabel = ucfirst($booking->payment_status);
                                            $amountColor = '#1f2937';
                                            $amountPrefix = 'RM';
                                        }
                                    @endphp

                                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px;">
                                        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 15px; align-items: start;">
                                            <!-- Event & Booking Info -->
                                            <div>
                                                <div style="font-weight: 600; color: #1f2937; margin-bottom: 8px;">
                                                    {{ $event->name ?? 'Event Unavailable' }}
                                                </div>
                                                <div style="font-size: 13px; color: #6b7280; margin-bottom: 3px;">
                                                    📅 {{ $event->date?->format('d M Y') ?? 'N/A' }}
                                                </div>
                                                <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">
                                                    📍 {{ $event->venue ?? 'N/A' }}
                                                </div>
                                                <div style="font-size: 12px; color: #6b7280;">
                                                    <strong>Booking ID:</strong> #{{ $booking->id }}
                                                </div>
                                            </div>

                                            <!-- Purchase Details -->
                                            <div>
                                                <div style="font-size: 12px; color: #6b7280; margin-bottom: 6px;">
                                                    <strong>Purchase Date:</strong><br>
                                                    {{ $booking->created_at->format('d M Y, H:i') }}
                                                </div>
                                                <div style="font-size: 12px; color: #6b7280; margin-bottom: 6px;">
                                                    <strong>Seats:</strong> {{ $booking->number_of_seats ?? 1 }}
                                                </div>
                                                <div style="font-size: 12px; color: #6b7280;">
                                                    <strong>Status:</strong>
                                                    <span style="color: {{ $statusColor }}; font-weight: 500;">
                                                        {{ $statusLabel }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Amount & Action -->
                                            <div style="text-align: right;">
                                                <div style="font-size: 20px; font-weight: bold; color: {{ $amountColor }}; margin-bottom: 10px;">
                                                    {{ $amountPrefix }}{{ number_format($booking->total_amount ?? 0, 2) }}
                                                </div>
                                                <a href="{{ route('bookings.show', $booking) }}" class="act-btn act-view" style="display: inline-block; margin-bottom: 6px;">
                                                    View Booking
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 30px; text-align: center; color: #1e40af;">
                                <div style="font-size: 40px; margin-bottom: 10px;">🛒</div>
                                <div style="font-weight: 600; margin-bottom: 5px;">No Purchase History</div>
                                <div style="font-size: 14px;">Start exploring and book your favorite events!</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection