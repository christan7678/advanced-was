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

            <div class="account-menu" style="padding: 20px;">
                <div style="max-width: 900px;">
                    <!-- Summary Stats -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 30px;">
                        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #059669;">{{ $user->bookings()->count() }}</div>
                            <div style="font-size: 12px; color: #6b7280;">Total Purchases</div>
                        </div>
                        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #2563eb;">{{ $user->bookings()->where('payment_status', 'completed')->count() }}</div>
                            <div style="font-size: 12px; color: #6b7280;">Completed</div>
                        </div>
                        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #dc2626;">{{ $user->bookings()->count() - $user->bookings()->where('payment_status', 'completed')->count() }}</div>
                            <div style="font-size: 12px; color: #6b7280;">Other Status</div>
                        </div>
                        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #7c3aed;">
                                ${{ number_format($user->bookings()->sum('total_amount'), 2) }}
                            </div>
                            <div style="font-size: 12px; color: #6b7280;">Total Spent</div>
                        </div>
                    </div>

                    <!-- Purchase List -->
                    <div>
                        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 15px; color: #1f2937;">
                            💳 All Purchases
                        </h3>

                        @php
                            $bookings = $user->bookings()
                                ->with('event')
                                ->orderByDesc('created_at')
                                ->get();
                        @endphp

                        @if($bookings->count() > 0)
                            <div style="display: grid; gap: 12px;">
                                @foreach($bookings as $booking)
                                    @php 
                                        $event = $booking->event;
                                        $isPaid = $booking->payment_status === 'completed';
                                    @endphp
                                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px;">
                                        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 15px; align-items: start;">
                                            <!-- Event & Booking Info -->
                                            <div>
                                                <div style="font-weight: 600; color: #1f2937; margin-bottom: 8px;">
                                                    {{ $event->name }}
                                                </div>
                                                <div style="font-size: 13px; color: #6b7280; margin-bottom: 3px;">
                                                    📅 {{ $event->date?->format('d M Y') ?? 'N/A' }}
                                                </div>
                                                <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">
                                                    📍 {{ $event->venue ?? 'N/A' }}
                                                </div>
                                                <div style="font-size: 12px; color: #6b7280;">
                                                    <strong>Booking ID:</strong> #{{$booking->id }}
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
                                                    <strong>Payment:</strong>
                                                    <span style="color: {{ $isPaid ? '#059669' : '#dc2626' }}; font-weight: 500;">
                                                        {{ ucfirst($booking->payment_status) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Amount & Action -->
                                            <div style="text-align: right;">
                                                <div style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 10px;">
                                                    ${{ number_format($booking->total_amount ?? 0, 2) }}
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