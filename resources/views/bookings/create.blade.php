@extends('layouts.app')

@section('content')
    <div style="max-width: 800px; margin: 40px auto; padding: 20px;">
        
        <!-- Back Button -->
        <div style="margin-bottom: 20px;">
            <a href="/profile" onclick="if(history.length > 1){ history.back(); return false; }" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                ← Back
            </a>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; padding: 16px; margin-bottom: 20px; color: #7f1d1d;">
                <strong>❌ Booking Error:</strong>
                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Booking Form Card -->
        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 30px;">
            
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 30px;">
                Book Your Tickets
            </h1>

            <form method="POST" action="{{ route('bookings.store') }}" style="display: grid; gap: 20px;">
                @csrf

                <!-- Event Selection (if not pre-selected) -->
                @if(!$event)
                    <div>
                        <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">
                            Select Event *
                        </label>
                        <select 
                            name="event_id" 
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                            required
                            id="eventSelect"
                            onchange="updateEventPrice()"
                        >
                            <option value="">-- Choose an event --</option>
                            @forelse(\App\Models\Event::where('status', '!=', 'sold_out')->orderBy('date')->get() as $evt)
                                <option value="{{ $evt->id }}" data-price="{{ $evt->price }}" data-available="{{ $evt->available_seats }}">
                                    {{ $evt->name }} - {{ $evt->date->format('d M Y') }} ({{ $evt->available_seats }} seats available)
                                </option>
                            @empty
                                <option disabled>No available events</option>
                            @endforelse
                        </select>
                        @error('event_id')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>
                @else
                    <!-- Event Details Display -->
                    <div style="background: #f0f9ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 15px;">
                        <div style="font-weight: 600; color: #1f2937; margin-bottom: 10px;">
                            {{ $event->name }}
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 13px; color: #6b7280;">
                            <div>
                                <strong>Date:</strong> {{ $event->date->format('d M Y') }} at {{ $event->time }}
                            </div>
                            <div>
                                <strong>Venue:</strong> {{ $event->venue }}
                            </div>
                            <div>
                                <strong>Price:</strong> ${{ number_format($event->price, 2) }} per ticket
                            </div>
                            <div>
                                <strong>Available:</strong> {{ $event->available_seats }} seats
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Event ID -->
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                @endif

                <!-- Number of Seats -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">
                        Number of Tickets *
                    </label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; align-items: center;">
                        <input 
                            type="number" 
                            name="number_of_seats" 
                            value="{{ old('number_of_seats', 1) }}"
                            min="1"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                            id="seatsInput"
                            required
                        >
                        <div id="seatInfo" style="font-size: 13px; color: #6b7280;">
                            @if($event)
                                Max {{ $event->available_seats }} seats available
                            @endif
                        </div>
                    </div>
                    @error('number_of_seats')
                        <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Price Summary (always visible) -->
                <div id="priceContainer" style="display: {{ $event ? 'block' : 'none' }};">
                    <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; display: grid; gap: 8px; margin-bottom: 15px;">
                        <div style="display: grid; grid-template-columns: 1fr auto; font-size: 13px; color: #6b7280;">
                            <div>Unit Price:</div>
                            <div id="unitPriceDisplay">RM{{ $event ? number_format($event->price, 2) : '0.00' }}</div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr auto; font-size: 13px; color: #6b7280;">
                            <div>Quantity:</div>
                            <div id="quantityDisplay">1</div>
                        </div>
                        <div style="border-top: 1px solid #d1d5db; padding-top: 8px; display: grid; grid-template-columns: 1fr auto; font-weight: 600; font-size: 18px; color: #059669;">
                            <div>Total Amount:</div>
                            <div id="totalPrice" style="font-family: monospace;">RM{{ $event ? number_format($event->price, 2) : '0.00' }}</div>
                        </div>
                    </div>

                    <!-- Display field for user (readonly) -->
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">
                            Amount to Pay
                        </label>
                        <input 
                            type="text" 
                            id="displayTotalAmount"
                            value="RM{{ $event ? number_format($event->price, 2) : '0.00' }}"
                            readonly
                            style="width: 100%; padding: 14px; border: 2px solid #059669; border-radius: 6px; font-size: 18px; font-weight: 700; color: #059669; background: #f0fdf4; cursor: not-allowed; text-align: center;"
                        >
                    </div>
                </div>

                <!-- Hidden field for form submission -->
                <input type="hidden" id="totalAmountInput" name="total_amount" value="{{ $event ? $event->price : 0 }}">
                

                <!-- Submit Button -->
                <button 
                    type="submit"
                    style="width: 100%; background: #2563eb; color: white; padding: 14px; border: none; border-radius: 6px; font-weight: 600; font-size: 16px; cursor: pointer; transition: background 0.3s; margin-top: 10px;"
                    onmouseover="this.style.background='#1d4ed8'"
                    onmouseout="this.style.background='#2563eb'"
                >
                    ✓ Confirm Booking
                </button>
            </form>

            <!-- Security Notice -->
            <div style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 8px; padding: 12px; margin-top: 20px; font-size: 13px; color: #166534;">
                 Your booking is secure and your payment information is encrypted.
            </div>
        </div>
    </div>

    <script>
        const eventSelect = document.getElementById('eventSelect');
        const seatsInput = document.getElementById('seatsInput');
        const quantityDisplay = document.getElementById('quantityDisplay');
        const totalPriceDisplay = document.getElementById('totalPrice');
        const totalAmountInput = document.getElementById('totalAmountInput');
        const displayTotalAmount = document.getElementById('displayTotalAmount');
        const unitPriceDisplay = document.getElementById('unitPriceDisplay');
        const priceContainer = document.getElementById('priceContainer');
        const seatInfo = document.getElementById('seatInfo');

        let currentEventPrice = {{ $event ? $event->price : 0 }};

        function updateEventPrice() {
            if (!eventSelect) return;
            const selectedOption = eventSelect.options[eventSelect.selectedIndex];
            if (!selectedOption.value) {
                priceContainer.style.display = 'none';
                return;
            }

            currentEventPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const maxSeats = parseInt(selectedOption.getAttribute('data-available')) || 0;
            
            // Update max seats
            seatsInput.max = maxSeats;
            seatsInput.value = 1;
            seatInfo.textContent = 'Max ' + maxSeats + ' seats available';

            // Show price container
            priceContainer.style.display = 'block';

            // Update prices
            updatePrice();
        }

        function updatePrice() {
            const quantity = parseInt(seatsInput.value) || 1;
            const total = (quantity * currentEventPrice).toFixed(2);
            const formattedTotal = 'RM' + parseFloat(total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            quantityDisplay.textContent = quantity;
            unitPriceDisplay.textContent = 'RM' + parseFloat(currentEventPrice).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            totalPriceDisplay.textContent = formattedTotal;
            totalAmountInput.value = total;
            displayTotalAmount.value = formattedTotal;
        }

        if (seatsInput) {
            seatsInput.addEventListener('input', updatePrice);
        }

        @if($event)
            priceContainer.style.display = 'block';
            updatePrice();
        @endif
    </script>
@endsection
