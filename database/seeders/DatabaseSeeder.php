<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Component;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $components = Component::factory(10)->create();

        $products = Product::factory(5)->create();

        foreach ($products as $product) {
            $randomComponents = $components->random(rand(2, 5));

            foreach ($randomComponents as $component) {
                DB::table('component_product')->insert([
                    'product_id' => $product->id,
                    'component_id' => $component->id,
                    'quantity' => rand(1, 10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
