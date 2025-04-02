<?php

namespace Database\Factories;

use App\Enums\ClientLegalFormEnum;
use App\Enums\ClientTypeEnum;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'phone' => '+380' . $this->faker->numerify('#########'),
            'client_type' => ClientTypeEnum::SUPPLIER->value,
            'legal_form' => ClientLegalFormEnum::LLC->value,
        ];
    }

    public function withFixedPayer()
    {
        return $this->state(fn () =>[
            'name' => $this->faker->company,
            'phone' => '+380' . $this->faker->numerify('#########'),
            'client_type' => ClientTypeEnum::PAYER->value,
            'legal_form' => Arr::random([
                ClientLegalFormEnum::PE,
                ClientLegalFormEnum::LLC,
                ClientLegalFormEnum::NGO,
                ClientLegalFormEnum::CHARITABLE_FOUNDATION,
            ])->value,
        ]);
    }

    public function withFixedReceiver()
    {
        return $this->state(fn () =>[
            'name' => $this->faker->name,
            'phone' => '+380' . $this->faker->numerify('#########'),
            'client_type' => ClientTypeEnum::RECEIVER->value,
            'legal_form' => ClientLegalFormEnum::INDIVIDUAL->value,
        ]);
    }
}
