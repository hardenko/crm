<?php

namespace App\Models;

use App\Enums\OrderStatusType;
use App\Enums\PaymentStatusType;
use App\Enums\StockMovementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Exception;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'payment_status' => PaymentStatusType::class,
            'order_status' => OrderStatusType::class,
        ];
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($order) {
            $product = Product::with('belongsToManyComponents')->find($order->product_id);
            if (!$product) {
                throw new Exception("Product not found.");
            }

            $order->total_price = $order->quantity * $product->price;

            foreach ($product->belongsToManyComponents as $component) {
                $neededQuantity = $component->pivot->quantity * $order->quantity;
                $warehouseItem = Warehouse::where('component_id', $component->id)->first();

                if (!$warehouseItem || $warehouseItem->quantity < $neededQuantity) {
                    throw new Exception("Not enough stock for component: $component->name. Required: $neededQuantity, Available: " . ($warehouseItem->quantity ?? 0));
                }
            }
        });

        static::created(function ($order) {
            DB::transaction(function () use ($order) {
                $product = Product::with('belongsToManyComponents')->find($order->product_id);

                foreach ($product->belongsToManyComponents as $component) {
                    $neededQuantity = $component->pivot->quantity * $order->quantity;

                    StockMovement::create([
                        'component_id' => $component->id,
                        'quantity' => $neededQuantity,
                        'type' => StockMovementType::Outgoing->value,
                        'comments' => "Auto deduction for order #$order->id"
                    ]);
                }
            });
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function payer(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'payer_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Client::class, 'receiver_id');
    }
}
