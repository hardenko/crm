<?php

namespace Database\Seeders;

use App\Enums\ClientLegalForm;
use App\Enums\ClientType;
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
            'client_type' => ClientType::Payer->value,
            'legal_form' => ClientLegalForm::LLC->value,
        ]);

        Client::create([
            'name' => 'Test Receiver',
            'phone' => '+380123456798',
            'client_type' => ClientType::Receiver->value,
            'legal_form' => ClientLegalForm::Individual->value,
        ]);

        Client::create([
            'name' => 'Test Supplier',
            'phone' => '+380123456799',
            'client_type' => ClientType::Supplier->value,
            'legal_form' => ClientLegalForm::SP->value,
        ]);
    }
}
