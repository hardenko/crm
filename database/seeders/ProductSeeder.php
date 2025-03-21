<?php

namespace Database\Seeders;

use App\Models\Component;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $components = Component::all();

        $products = Product::factory(5)->create();

        foreach ($products as $product) {
            $randomComponents = $components->random(3);

            foreach ($randomComponents as $component) {
                DB::table('component_product')->insert([
                    'product_id' => $product->id,
                    'component_id' => $component->id,
                    'quantity' => rand(1, 3),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $productTest = Product::factory()->create([
                'name' => 'Test Product',
                'price' => '10000.00'
            ]
        );

        $randomComponents = $components->random(3);

        foreach ($randomComponents as $component) {
            DB::table('component_product')->insert([
                'product_id' => $productTest->id,
                'component_id' => $component->id,
                'quantity' => rand(1, 3),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
