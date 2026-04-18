<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 200; $i++) {
            User::create([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@test.com',
                'password' => Hash::make('password'),
            ]);
        }
    }
}