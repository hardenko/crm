<?php

namespace Tests\Unit;

use App\Enums\ClientTypeEnum;
use App\Enums\OrderStatusTypeEnum;
use App\Enums\PaymentStatusTypeEnum;
use App\Enums\StockMovementTypeEnum;
use App\Models\Client;
use App\Models\Component;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

final class OrderObserverTest extends TestCase
{
    public function testTotalPriceIsCalculatedOnCreating(): void
    {
        $component = Component::factory()->create();
        $product = Product::factory()->create(['price' => 100]);
        $product->componentsRelation()->attach($component->id, ['quantity' => 2]);
        $payer = Client::factory()->create(['client_type' => ClientTypeEnum::PAYER]);
        $receiver = Client::factory()->create(['client_type' => ClientTypeEnum::RECEIVER]);

        $order = Order::factory()->create([
            'product_id' => $product->id,
            'quantity' => 3,
            'payer_id' => $payer->id,
            'receiver_id' => $receiver->id,
            'payment_status' => PaymentStatusTypeEnum::PENDING,
            'order_status' => OrderStatusTypeEnum::PENDING,
        ]);

        $this->assertEquals(300, $order->total_price);
    }

    public function testStockMovementsAreCreatedOnCreated(): void
    {
        $component = Component::factory()->create();
        $product = Product::factory()->create();
        $product->componentsRelation()->attach($component->id, ['quantity' => 2]);
        $payer = Client::factory()->create(['client_type' => ClientTypeEnum::PAYER]);
        $receiver = Client::factory()->create(['client_type' => ClientTypeEnum::RECEIVER]);

        $order = Order::factory()->create([
            'product_id' => $product->id,
            'quantity' => 3,
            'payer_id' => $payer->id,
            'receiver_id' => $receiver->id,
            'payment_status' => PaymentStatusTypeEnum::PENDING,
            'order_status' => OrderStatusTypeEnum::PENDING,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'component_id' => $component->id,
            'quantity' => 6,
            'type' => StockMovementTypeEnum::OUTGOING->value,
            'comments' => "Auto deduction for order #{$order->id}",
        ]);
    }

    public function testLogsWarningIfNoComponents(): void
    {
        Log::shouldReceive('warning')
            ->once()
            ->withArgs(fn ($message) => str_contains($message, 'has no components.'));

        $product = Product::factory()->create();
        $payer = Client::factory()->create(['client_type' => ClientTypeEnum::PAYER]);
        $receiver = Client::factory()->create(['client_type' => ClientTypeEnum::RECEIVER]);

        Order::factory()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'payer_id' => $payer->id,
            'receiver_id' => $receiver->id,
            'payment_status' => PaymentStatusTypeEnum::PENDING,
            'order_status' => OrderStatusTypeEnum::PENDING,
        ]);
    }
}
