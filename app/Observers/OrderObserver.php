<?php

namespace App\Observers;

use App\Enums\StockMovementTypeEnum;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderObserver
{
    public function creating(Order $order): void
    {
        $product = Product::find($order->product_id);
        $order->total_price = ($product?->price ?? 0) * ($order->quantity ?? 1);
    }

    public function created(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $product = Product::with('componentsRelation')->find($order->product_id);

            if (!$product) {
                throw new Exception("Product not found for Order #$order->id");
            }

            foreach ($product->componentsRelation as $component) {
                $neededQuantity = $component->pivot->quantity * $order->quantity;

                StockMovement::create([
                    'component_id' => $component->id,
                    'quantity' => $neededQuantity,
                    'type' => StockMovementTypeEnum::OUTGOING->value,
                    'comments' => "Auto deduction for order #$order->id"
                ]);
            }

            if ($product->componentsRelation->isEmpty()) {
                logger()->warning("Product #$product->id has no components.");
            }
        });
    }
}
