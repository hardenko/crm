<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Component extends Model
{
    use HasFactory;

    protected $table = 'components';
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

    public function supplier()
    {
        return $this->belongsTo(Client::class, 'supplier_id')->where('client_type', 'supplier');
    }

    public function warehouseItem(): HasOne
    {
        return $this->hasOne(WarehouseItem::class, 'component_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($component) {
            WarehouseItem::create([
                'component_id' => $component->id,
                'quantity' => 0,
            ]);
        });
    }
}
