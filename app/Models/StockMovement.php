<?php

namespace App\Models;

use App\Enums\StockMovementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Exception;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stock_movements';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'type' => StockMovementType::class,
        ];
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'supplier_id');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($movement) {
            $warehouseItem = Warehouse::where('component_id', $movement->component_id)->first();
            if (!$warehouseItem) {
                throw new Exception("Component '{$movement->component->name}' is not available in warehouse.");
            }
        });

        static::created(function ($movement) {
            DB::transaction(function () use ($movement) {
                $warehouseItem = Warehouse::where('component_id', $movement->component_id)->lockForUpdate()->first();

                if (!$warehouseItem) {
                    throw new Exception("Critical error: Component '{$movement->component->name}' not found in warehouse.");
                }

                if ($movement->type->isIncoming()) {
                    $warehouseItem->increment('quantity', $movement->quantity);
                } elseif ($movement->type->isOutgoing()) {
                    $warehouseItem->decrement('quantity', $movement->quantity);
                }
            });
        });
    }
}
