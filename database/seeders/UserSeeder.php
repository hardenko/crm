<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->withRole('admin')->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->withRole('manager')->create([
            'name' => 'Test Manager',
            'email' => 'testmanager@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->withRole('wh_manager')->create([
            'name' => 'Test Wh_manager',
            'email' => 'testwh_manager@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
