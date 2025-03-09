<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\PaymentStatusType;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'payment_status' => PaymentStatusType::class,
    ];
    public function belongsToManyComponents(): BelongsToMany
    {
        return $this->belongsToMany(Component::class, 'component_product', 'product_id', 'component_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
    public function components(): HasMany
    {
        return $this->hasMany(ProductComponent::class, 'product_id', 'id');
    }
}
