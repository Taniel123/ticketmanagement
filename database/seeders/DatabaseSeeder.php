<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_approved' => true,
        ]);

        // Create a support user
        User::create([
            'name' => 'Support User',
            'email' => 'support@example.com',
            'password' => Hash::make('password123'),
            'role' => 'support',
            'is_approved' => true,
        ]);

        // Create a regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'is_approved' => true,
        ]);

        // Create a few unapproved users for testing
        foreach (range(1, 5) as $index) {
            User::create([
                'name' => "Unapproved User $index",
                'email' => "unapproved$index@example.com",
                'password' => Hash::make('password123'),
                'role' => 'user',
                'is_approved' => false,
            ]);
        }

        // Create a test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'is_approved' => true,
        ]);

        // 🔽 Seed Sample Tickets
        $statuses = ['open', 'ongoing', 'closed'];
        $priorities = ['low', 'medium', 'high'];

        foreach (range(1, 10) as $i) {
            DB::table('tickets')->insert([
                'title' => "Sample Ticket $i",
                'description' => "This is a sample description for ticket #$i.",
                'priority' => $priorities[array_rand($priorities)],
                'status' => $statuses[array_rand($statuses)],
                'user_id' => rand(1, 5), // assuming at least 5 users exist
                'created_by' => rand(1, 3), // admin/support/user
                'created_at' => Carbon::now()->subDays(rand(0, 10)),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
