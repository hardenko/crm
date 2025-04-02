<?php

namespace App\Models;

use App\Enums\OrderStatusTypeEnum;
use App\Enums\PaymentStatusTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'payment_status' => PaymentStatusTypeEnum::class,
            'order_status' => OrderStatusTypeEnum::class,
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function payer(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'payer_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'receiver_id');
    }
}
