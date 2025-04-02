<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function componentsRelation(): BelongsToMany
    {
        return $this->belongsToMany(Component::class, 'product_components', 'product_id', 'component_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
    public function productComponentsRelation(): HasMany
    {
        return $this->hasMany(ProductComponent::class, 'product_id', 'id');
    }
}
