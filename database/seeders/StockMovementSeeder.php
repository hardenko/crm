<?php

namespace Database\Seeders;

use App\Models\StockMovement;
use Illuminate\Database\Seeder;
use App\Models\Component;

class StockMovementSeeder extends Seeder
{
    public function run()
    {
        $components = Component::all();

        foreach ($components as $component) {
            StockMovement::factory()->create([
                'component_id' => $component->id,
                'supplier_id' => $component->supplier_id
            ]);
        }
    }
}
