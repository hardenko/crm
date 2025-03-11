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
        Client::factory()->withFixedPayer()->create();
        Client::factory()->withFixedReceiver()->create();
        Client::factory()->count(5)->create();
    }
}
