<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use App\Models\Component;

class ComponentSeeder extends Seeder
{
    public function run(): void
    {
        Component::factory(10)->create();
        Component::factory()->create([
                'name' => 'Test Component',
            ]
        );
    }
}
