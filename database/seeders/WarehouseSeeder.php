<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use App\Models\Component;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $components = Component::all();

        foreach ($components as $component) {
            Warehouse::firstOrCreate([
                'component_id' => $component->id,
            ], [
                'quantity' => 0
            ]);
        }
    }
}
