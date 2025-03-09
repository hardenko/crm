<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class LocalDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->withRole('admin')->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->withRole('manager')->create([
            'name' => 'Test Manager',
            'email' => 'testmanager@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->withRole('warehouse')->create([
            'name' => 'Test Warehouse',
            'email' => 'testwarehouse@example.com',
            'password' => bcrypt('password'),
        ]);

        Client::create([
            'name' => 'Test Payer',
            'phone' => '+380123456789',
            'type' => 'payer',
        ]);

        Client::create([
            'name' => 'Test Receiver',
            'phone' => '+380123456798',
            'type' => 'receiver',
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
