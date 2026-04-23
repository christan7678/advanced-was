<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::where('role', 'super_admin')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'phone_number' => '60123285123',
                'password' => Hash::make('@superAdmin123'),
                'role' => 'super_admin',
            ]);
        }
    }
}
