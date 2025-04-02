<?php

namespace App\Observers;

use App\Models\Component;
use App\Models\Warehouse;

class ComponentObserver
{
    /**
     * Handle the Component "created" event.
     */
    public function created(Component $component): void
    {
        Warehouse::create([
            'component_id' => $component->id,
            'quantity' => 0,
        ]);
    }
}
