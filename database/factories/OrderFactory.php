<?php

namespace Database\Factories;

use App\Enums\OrderStatusTypeEnum;
use App\Enums\PaymentStatusTypeEnum;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class OrderFactory extends Factory
{
    protected $model = Order::class;
    public function definition(): array
    {
        $product = Product::inRandomOrder()->first();
        $payer = Client::inRandomOrder()->where('client_type', 'payer')->first();
        $receiver = Client::inRandomOrder()->where('client_type', 'receiver')->first();

        return [
            'product_id' => $product?->id,
            'quantity' => 1,
            'payer_id' => $payer?->id,
            'receiver_id' => $receiver?->id,
            'payment_status' => Arr::random(PaymentStatusTypeEnum::cases())->value,
            'order_status' => Arr::random(OrderStatusTypeEnum::cases())->value,
            'comments' => $this->faker->sentence,
        ];
    }
}
