<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ProductComponent extends Model
{
    protected $table = 'component_product';
    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast. *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function components(): BelongsTo
    {
        return $this->belongsTo(Component::class, 'component_id', 'id');
    }
}
