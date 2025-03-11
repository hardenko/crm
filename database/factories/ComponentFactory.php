<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Component;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComponentFactory extends Factory
{
    protected $model = Component::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'supplier_id' => Client::where('client_type', 'supplier')->inRandomOrder()->first()?->id,
        ];
    }
}
