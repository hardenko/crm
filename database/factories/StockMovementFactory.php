<?php

namespace Database\Factories;

use App\Enums\StockMovementType;
use App\Models\Component;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockMovementFactory extends Factory
{
    protected $model = StockMovement::class;
    public function definition(): array
    {
        $component = Component::inRandomOrder()->first();

        return [
            'component_id' => $component?->id,
            'supplier_id' => $component ? $component->supplier_id : null,
            'quantity' => 50,
            'price' => $this->faker->numberBetween(1,10000),
            'type' => StockMovementType::Incoming->value,
            'comments' => $this->faker->sentence(),
        ];
    }
}
