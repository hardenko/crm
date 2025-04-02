<?php

namespace Tests\Unit;

use App\Enums\StockMovementTypeEnum;
use App\Models\Component;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Tests\TestCase;

final class StockMovementObserverTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Component::flushEventListeners();
    }

    public function testQuantityIsIncrementedForIncomingMovement(): void
    {
        $component = Component::factory()->create();
        Warehouse::create([
            'component_id' => $component->id,
            'quantity' => 5,
        ]);

        StockMovement::create([
            'component_id' => $component->id,
            'quantity' => 3,
            'type' => StockMovementTypeEnum::INCOMING,
            'comments' => 'Test incoming',
        ]);

        $this->assertDatabaseHas('warehouse', [
            'component_id' => $component->id,
            'quantity' => 8,
        ]);
    }

    public function testQuantityIsDecrementedForOutgoingMovement(): void
    {
        $component = Component::factory()->create();
        Warehouse::create([
            'component_id' => $component->id,
            'quantity' => 10,
        ]);

        StockMovement::create([
            'component_id' => $component->id,
            'quantity' => 4,
            'type' => StockMovementTypeEnum::OUTGOING,
            'comments' => 'Test outgoing',
        ]);

        $this->assertDatabaseHas('warehouse', [
            'component_id' => $component->id,
            'quantity' => 6,
        ]);
    }

    public function testExceptionIsThrownIfComponentNotFoundInWarehouse(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('not found in warehouse');

        $component = Component::factory()->create();

        StockMovement::create([
            'component_id' => $component->id,
            'quantity' => 1,
            'type' => StockMovementTypeEnum::INCOMING,
            'comments' => 'Should fail',
        ]);
    }
}
