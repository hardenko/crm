<?php

namespace App\Observers;

use App\Enums\StockMovementTypeEnum;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Exception;
use Illuminate\Support\Facades\DB;

class StockMovementObserver
{
    /**
     * Handle the StockMovement "created" event.
     */
    public function created(StockMovement $movement): void
    {
        DB::transaction(function () use ($movement) {
            $warehouseItem = Warehouse::where('component_id', $movement->component_id)->lockForUpdate()->first();

            if (!$warehouseItem) {
                throw new Exception("Critical error: Component '{$movement->component->name}' not found in warehouse.");
            }

            if ($movement->type == StockMovementTypeEnum::INCOMING) {
                $warehouseItem->increment('quantity', $movement->quantity);
            } elseif ($movement->type == StockMovementTypeEnum::OUTGOING) {
                $warehouseItem->decrement('quantity', $movement->quantity);
            }
        });
    }
}
