<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'type' => $this->faker->word(),
            'price'=> $this->faker->numberBetween(1,1000),
            'payment_status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'payer' => $this->faker->name(),
            'receiver' => $this->faker->name()
        ];
    }
}
