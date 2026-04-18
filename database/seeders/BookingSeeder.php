<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Event;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $users = User::pluck('id')->toArray();
        $events = Event::all();

        for ($i = 1; $i <= 100; $i++) {

            $event = $events->random();

            // make concerts slightly more popular
            if ($event->category_id == 5 && rand(1, 100) <= 60) {
                $event = $events->where('category_id', 5)->random();
            }

            $userId = $users[array_rand($users)];
            $seats = rand(1, 5);

            // spread bookings across last 10 days
            $date = Carbon::now()->subDays(rand(0, 9))->subMinutes(rand(0, 1440));

            $booking = Booking::create([
    'user_id' => $userId,
    'event_id' => $event->id,
    'number_of_seats' => $seats,
    'total_amount' => $event->price * $seats,
    'booking_status' => 'pending',
    'payment_status' => 'completed',
    'created_at' => $date,
    'updated_at' => $date,
]);

$booking->update([
    'booking_code' => 'BK' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
]);
        }
    }

    private function randomStatus()
    {
        $statuses = ['confirmed', 'pending', 'cancelled'];
        return $statuses[array_rand($statuses)];
    }

    private function randomPaymentStatus()
    {
        $statuses = ['completed', 'pending', 'cancelled'];
        return $statuses[array_rand($statuses)];
    }
}