<?php

namespace Tests\Unit;

use App\Models\Component;
use Tests\TestCase;

final class ComponentObserverTest extends TestCase
{
    public function testWarehouseIsCreatedWhenComponentIsCreated(): void
    {
        $component = Component::factory()->create();

        $this->assertDatabaseHas('warehouse', [
            'component_id' => $component->id,
            'quantity' => 0,
        ]);
    }
}
