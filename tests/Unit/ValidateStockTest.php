<?php

namespace Tests\Unit;

use App\Models\Component;
use App\Models\Product;
use App\Models\Warehouse;
use App\Rules\ValidateStock;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ValidateStockTest extends TestCase
{
    public function testValidationPassesIfEnoughStock()
    {
        $component = Component::factory()->create();

        Warehouse::updateOrCreate(
            ['component_id' => $component->id],
            ['quantity' => 10],
        );

        $product = Product::factory()->create();
        $product->componentsRelation()->attach($component->id, ['quantity' => 2]);

        $validator = Validator::make(
            ['quantity' => 3],
            ['quantity' => [new ValidateStock($product->id)]]
        );

        $this->assertTrue($validator->passes());
    }

    public function testValidationFailsIfNotEnoughStock()
    {
        $component = Component::factory()->create();

        Warehouse::updateOrCreate(
            ['component_id' => $component->id],
            ['quantity' => 5],
        );

        $product = Product::factory()->create();
        $product->componentsRelation()->attach($component->id, ['quantity' => 2]);

        $validator = Validator::make(
            ['quantity' => 3],
            ['quantity' => [new ValidateStock($product->id)]]
        );

        $this->assertFalse($validator->passes());
    }
}
