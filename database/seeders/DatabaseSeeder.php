<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'), // Hashing the password
            'role' => 'admin', // Admin role
            'is_approved' => true, // Always approved
        ]);

        // Create a support user
        User::create([
            'name' => 'Support User',
            'email' => 'support@example.com',
            'password' => Hash::make('password123'), // Hashing the password
            'role' => 'support', // Support role
            'is_approved' => true, // Always approved
        ]);

        // Create a regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'), // Hashing the password
            'role' => 'user', // User role
            'is_approved' => true, // Approved
        ]);

        // Create a few unapproved users for testing
        foreach (range(1, 5) as $index) {
            User::create([
                'name' => "Unapproved User $index",
                'email' => "unapproved$index@example.com",
                'password' => Hash::make('password123'), // Hashing the password
                'role' => 'user', // User role
                'is_approved' => false, // Not approved
            ]);
        }

        // Create a test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('12345678'), // Hashing the password
            'role' => 'user', // Regular user
            'is_approved' => true, // Approved
        ]);
    }
}