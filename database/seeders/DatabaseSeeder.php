<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin Apay',
            'email' => 'admin@apaysbooks.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create regular user
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'user@apaysbooks.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // Run book seeder
        $this->call(BookSeeder::class);
    }
}
