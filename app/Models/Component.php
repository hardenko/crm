<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Component extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function belongsToManyProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'component_product', 'component_id', 'product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function products(): HasMany
    {
        return $this->hasMany(ProductComponent::class, 'component_id', 'id');
    }
}
