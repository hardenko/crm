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
        Client::create([
            'name' => 'Test Payer',
            'phone' => '+380123456789',
            'client_type' => 'payer',
        ]);

        Client::create([
            'name' => 'Test Receiver',
            'phone' => '+380123456798',
            'client_type' => 'receiver',
        ]);
    }
}
