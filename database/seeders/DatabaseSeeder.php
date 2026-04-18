<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\BookingSeeder;
use Database\Seeders\TicketSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            BookingSeeder::class,
            TicketSeeder::class,
        ]);
    }
}