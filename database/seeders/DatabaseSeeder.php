<?php

namespace Database\Seeders;

use App\Models\Component;
use App\Models\WarehouseItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (App::environment('local')) {
            $this->call(UserSeeder::class);
            $this->call(ClientSeeder::class);
            $this->call(ComponentSeeder::class);
            $this->call(ProductSeeder::class);

            $components = Component::all(); //TODO

            foreach ($components as $component) {
                WarehouseItem::firstOrCreate([
                    'component_id' => $component->id,
                ], [
                    'quantity' => 0
                ]);
            }
        }
    }

}
