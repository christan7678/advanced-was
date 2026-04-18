<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\Booking;

class TicketSeeder extends Seeder
{
    public function run()
    {
        $qrList = [
            'storage/qrs/qr1.jpg',
            'storage/qrs/qr2.jpg',
            'storage/qrs/qr3.jpg'
        ];

        $bookings = Booking::all();

        foreach ($bookings as $booking) {

            for ($i = 1; $i <= $booking->number_of_seats; $i++) {

                Ticket::create([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'event_id' => $booking->event_id,
                    'ticket_code' => 'TK' . $booking->id . '-' . $i,
                    'qr_code_path' => $qrList[$i % 3],
                ]);
            }
        }
    }
}