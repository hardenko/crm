<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\ProductController;
use Database\Seeders\ClientSeeder;
use Database\Seeders\ComponentSeeder;
use Database\Seeders\ProductSeeder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(ProductController::class)]
final class ProductControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ClientSeeder::class);
        $this->seed(ComponentSeeder::class);
        $this->seed(ProductSeeder::class);
    }

    #[DataProvider('provideGetProductList')]
    public function testGetProductList($expectedStatus, $expectedResponse, $payload = []): void
    {
        $response = $this->makeCall(
            "/api/product-list",
            $payload,
        );

        $response->assertStatus($expectedStatus)
            ->assertJsonStructure($expectedResponse);
    }

    #[DataProvider('provideInvalidQuery')]
    public function testInvalidQuery($payload, $expectedStatus): void
    {
        $response = $this->makeCall(
            "/api/product-list",
            $payload,
        );

        $response->assertStatus($expectedStatus);
    }

    public static function provideGetProductList(): array
    {
        return [
            'get product list status 200 with name filters ' => [
                'expectedStatus' => 200,
                'expectedResponse' => self::successResponse(),
                'payload' => [
                    'name' => 'Test Product'
                ],
            ],
            'get product list status 200 with price filters ' => [
                'expectedStatus' => 200,
                'expectedResponse' => self::successResponse(),
                'payload' => [
                    'price' => '10000.00'
                ],
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
            'status 422 with string price' => [
                'payload' => ['price' => 'one'],
                'expectedStatus' => 422,
            ],
            'status 422 with negative value price' => [
                'payload' => ['price' => -1],
                'expectedStatus' => 422,
            ]
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
                    'product_id',
                    'name',
                    'type',
                    'price',
                    'components' => [
                        '*' => [
                            'component_id',
                            'name',
                            'description',
                            'supplier_id',
                            'quantity',
                            'created'
                        ]
                    ],
                    'created',
                ]
            ],
            "errors",
            "status",
        ];
    }
}
