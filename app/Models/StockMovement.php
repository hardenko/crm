<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stock_movements';
    protected $guarded = ['id'];

    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Client::class, 'supplier_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($movement) {
            if ($movement->type === 'outgoing') {
                $warehouseItem = WarehouseItem::where('component_id', $movement->component_id)->first();

                if (!$warehouseItem || $warehouseItem->quantity < $movement->quantity) {
                    throw new \Exception("Not enough stock available. Available: " . ($warehouseItem->quantity ?? 0));
                }
            }
        });

        static::created(function ($movement) {
            $warehouseItem = WarehouseItem::firstOrCreate(['component_id' => $movement->component_id]);

            if ($movement->type === 'incoming') {
                $warehouseItem->increment('quantity', $movement->quantity);
            } elseif ($movement->type === 'outgoing') {
                $warehouseItem->decrement('quantity', $movement->quantity);
            }
        });
    }
}
