<?php

namespace Database\Seeders;

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
        }
    }

}
