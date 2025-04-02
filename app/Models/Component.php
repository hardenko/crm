<?php

namespace App\Models;

use App\Enums\ClientTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Component extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function productsRelation(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_components', 'component_id', 'product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function productComponentsRelation(): HasMany
    {
        return $this->hasMany(ProductComponent::class, 'component_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Client::class, 'supplier_id')->where('client_type', ClientTypeEnum::SUPPLIER);
    }
}
