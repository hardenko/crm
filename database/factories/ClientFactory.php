<?php

namespace Database\Factories;

use App\Enums\ClientLegalForm;
use App\Enums\ClientType;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'phone' => '+380' . $this->faker->numerify('#########'),
            'client_type' => ClientType::Supplier->value,
            'legal_form' => ClientLegalForm::LLC->value,
        ];
    }

    public function withFixedPayer()
    {
        return $this->state(fn () =>[
            'name' => 'Test Payer',
            'phone' => '+380123456789',
            'client_type' => ClientType::Payer->value,
            'legal_form' => ClientLegalForm::LLC->value,
        ]);
    }

    public function withFixedReceiver()
    {
        return $this->state(fn () =>[
            'name' => 'Test Receiver',
            'phone' => '+380123456798',
            'client_type' => ClientType::Receiver->value,
            'legal_form' => ClientLegalForm::Individual->value,
        ]);
    }
}
