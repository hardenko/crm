<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\ComponentController;
use Database\Seeders\ClientSeeder;
use Database\Seeders\ComponentSeeder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(ComponentController::class)]
final class ComponentControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ClientSeeder::class);
        $this->seed(ComponentSeeder::class);
    }

    #[DataProvider('provideGetComponentList')]
    public function testGetComponentList($expectedStatus, $expectedResponse, $payload = []): void
    {
        $response = $this->makeCall(
            "/api/component-list",
            $payload,
        );

        $response->assertStatus($expectedStatus)
            ->assertJsonStructure($expectedResponse);
    }

    #[DataProvider('provideInvalidQuery')]
    public function testInvalidQuery($payload, $expectedStatus): void
    {
        $response = $this->makeCall(
            "/api/user-list",
            $payload,
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
//            'get client list status 200 with supplier id filters' => [ TODO
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
            //TODO ? тест для supplier_id
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
                    'created',
                ]
            ],
            "errors",
            "status",
        ];
    }
}
