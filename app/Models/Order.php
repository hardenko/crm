<?php

namespace App\Models;

use App\Enums\OrderStatusType;
use App\Enums\PaymentStatusType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'payment_status' => PaymentStatusType::class,
        'order_status' => OrderStatusType::class,
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function payer()
    {
        return $this->belongsTo(Client::class, 'payer_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Client::class, 'receiver_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->total_price = $order->quantity * $order->product->price;
        });

        static::updating(function ($order) {
            $order->total_price = $order->quantity * $order->product->price;
        });
    }
}
