<?php

namespace Tests\Unit;

use App\Models\Component;
use App\Models\Warehouse;
use App\Rules\IsComponentExistsInWarehouse;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class IsComponentExistsInWarehouseTest extends TestCase
{
    public function testValidationPassesIfComponentExistsInWarehouse(): void
    {
        $component = Component::factory()->create();

        Warehouse::create([
            'component_id' => $component->id,
            'quantity' => 10,
        ]);

        $validator = Validator::make(
            ['component_id' => $component->id],
            ['component_id' => [new IsComponentExistsInWarehouse]]
        );

        $this->assertTrue($validator->passes());
    }

    public function testValidationFailsIfComponentDoesNotExistInWarehouse(): void
    {
        Component::flushEventListeners();

        $component = Component::factory()->create();

        $validator = Validator::make(
            ['component_id' => $component->id],
            ['component_id' => [new IsComponentExistsInWarehouse]]
        );

        $this->assertFalse($validator->passes());
        $this->assertEquals(
            'The selected component is not available in warehouse.',
            $validator->errors()->first('component_id')
        );
    }
}
