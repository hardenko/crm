<?php

namespace Tests\Feature;

use App\Enums\ClientLegalFormEnum;
use App\Enums\ClientTypeEnum;
use App\Http\Controllers\Api\ComponentController;
use App\Models\Client;
use App\Models\Component;
use App\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\Permission\Models\Permission;

#[CoversClass(ComponentController::class)]
final class ComponentControllerTest extends TestCase
{
    protected int $supplierId;

    protected function setUp(): void
    {
        parent::setUp();

        $supplier = Client::factory()->create([
            'name' => 'Test Supplier',
            'phone' => '+380111111112',
            'bank_account' => 'Test Bank Account',
            'legal_form' => ClientLegalFormEnum::LLC->value,
            'client_type' => ClientTypeEnum::SUPPLIER->value,
        ]);

        Component::factory()->create([
            'name' => 'Test Component',
            'description' => 'Test Description',
            'supplier_id' => $supplier->id,
        ]);

        $this->supplierId = $supplier->id;
    }

    #[DataProvider('provideGetComponentList')]
    public function testGetComponentList($expectedStatus, $expectedResponse, $payload = []): void
    {
        if (!isset($payload['supplier_id'])) {
            $payload['supplier_id'] = $this->supplierId;
        }

        $response = $this->makeCall(
            "/api/components",
            $payload,
        );

        $response->assertStatus($expectedStatus)
            ->assertJsonStructure($expectedResponse);
    }

    #[DataProvider('provideInvalidQuery')]
    public function testInvalidQuery($payload, $expectedStatus): void
    {
        $response = $this->makeCall(
            "/api/components",
            $payload,
        );

        $response->assertStatus($expectedStatus);
    }

    #[DataProvider('provideAddComponent')]
    public function testAddComponent($payload, $expectedStatus): void
    {
        if (!isset($payload['supplier_id'])) {
            $payload['supplier_id'] = $this->supplierId;
        }

        Permission::firstOrCreate(['name' => 'add component']);

        $user = User::factory()->create();
        $user->givePermissionTo('add component');

        $response = $this->actingAs($user)->postJson(
            '/api/components',
            $payload
        );

        $response->assertStatus($expectedStatus);
    }

    public static function provideGetComponentList(): array
    {
        return [
            'get client list status 200 with name filters ' => [
                'expectedStatus' => 200,
                'expectedResponse' => self::successResponse(),
                'payload' => [
                    'name' => 'Test Component'
                ],
            ],
            'get client list status 200 with supplier id filters' => [
                'expectedStatus' => 200,
                'expectedResponse' => self::successResponse(),
                'payload' => [],
            ],
        ];
    }

    public static function provideInvalidQuery(): array
    {
        return [
            'status 422 with too long name' => [
                'payload' => ['name' => str_repeat('a', 256)],
                'expectedStatus' => 422,
            ],
            'status 422 with numeric name' => [
                'payload' => ['name' => 12345],
                'expectedStatus' => 422,
            ],
            'status 422 with invalid supplier id' => [
                'payload' => ['supplier_id' => 99999,],
                'expectedStatus' => 422,
            ],
        ];
    }

    public static function provideAddComponent(): array
    {
        return [
            'valid component creation' => [
                'payload' => [
                    'name' => 'New Component',
                    'description' => 'Valid component description',
                ],
                'expectedStatus' => 201,
            ],
            'invalid supplier id' => [
                'payload' => [
                    'name' => 'Component',
                    'supplier_id' => 99999,
                ],
                'expectedStatus' => 422,
            ],
            'missing name field' => [
                'payload' => [
                    'description' => 'Missing name',
                ],
                'expectedStatus' => 422,
            ],
        ];
    }

    protected function getHttpMethod(): string
    {
        return 'GET';
    }

    private static function successResponse(): array
    {
        return [
            "message",
            'data' => [
                '*' => [
                    'component_id',
                    'name',
                    'description',
                    'supplier_id',
                    'created_at',
                ]
            ],
            "errors",
            "status",
        ];
    }
}
