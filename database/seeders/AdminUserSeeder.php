<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        User::firstOrCreate(
            ['email' => 'admin@qaa-a.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]
        );

        // Also create a test owner and customer
        User::firstOrCreate(
            ['email' => 'owner@halls-booking.com'],
            [
                'name' => 'Hall Owner',
                'password' => bcrypt('owner123'),
                'role' => 'owner',
            ]
        );

        User::firstOrCreate(
            ['email' => 'customer@halls-booking.com'],
            [
                'name' => 'Customer',
                'password' => bcrypt('customer123'),
                'role' => 'customer',
            ]
        );
    }
}
