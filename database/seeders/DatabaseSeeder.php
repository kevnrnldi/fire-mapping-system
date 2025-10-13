<?php

namespace Database\Seeders;

use App\Models\GuestReport;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'password' => bcrypt('password'),
        // ]);

        GuestReport::factory()->create([
            'name' => 'John Doe',
            'contact' => '1234567890',
            'address' => '123 Main St',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',

        ]);
    }
}
