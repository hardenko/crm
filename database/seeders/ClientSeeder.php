<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::factory()->withFixedPayer()->count(10)->create();
        Client::factory()->withFixedReceiver()->count(10)->create();
        Client::factory()->count(5)->create();
    }
}
