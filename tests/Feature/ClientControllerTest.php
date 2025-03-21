<?php

namespace Tests\Feature;

use App\Enums\ClientLegalForm;
use App\Enums\ClientType;
use App\Http\Controllers\Api\ClientController;
use Database\Seeders\ClientSeeder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(ClientController::class)]
final class ClientControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ClientSeeder::class);
    }

    #[DataProvider('provideGetClientList')]
    public function testGetClientList($expectedStatus, $expectedResponse, $payload = []): void
    {
        $response = $this->makeCall(
            "/api/client-list",
            $payload,
        );

        $response->assertStatus($expectedStatus)
            ->assertJsonStructure($expectedResponse);
    }

    #[DataProvider('provideInvalidQuery')]
    public function testInvalidQuery($payload, $expectedStatus): void
    {
        $response = $this->makeCall(
            "/api/client-list",
            $payload,
        );

        $response->assertStatus($expectedStatus);
    }

    public static function provideGetClientList(): array
    {
        return [
            'get client list status 200 with name filters ' => [
                'expectedStatus' => 200,
                'expectedResponse' => self::successResponse(),
                'payload' => [
                    'name' => 'Test Client'
                ],
            ],
            'get client list status 200 with phone filters' => [
                'expectedStatus' => 200,
                'expectedResponse' => self::successResponse(),
                'payload' => [
                    'phone' => '+380111111111',
                ],
            ],
            'get client list status 200 with legal form filters' => [
                'expectedStatus' => 200,
                'expectedResponse' => self::successResponse(),
                'payload' => [
                    'legal_form' => ClientLegalForm::LLC->value,
                ],
            ],
            'get client list status 200 with bank account filters' => [
                'expectedStatus' => 200,
                'expectedResponse' => self::successResponse(),
                'payload' => [
                    'bank_account' => 'Test Bank Account',
                ],
            ],
            'get client list status 200 with client type filters' => [
                'expectedStatus' => 200,
                'expectedResponse' => self::successResponse(),
                'payload' => [
                    'client_type' => ClientType::Payer->value,
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
            'status 422 with invalid legal_form enum' => [
                'payload' => ['legal_form' => 'invalid_legal_form'],
                'expectedStatus' => 422,
            ],
            'status 422 with invalid client_type enum' => [
                'payload' => ['client_type' => 'invalid_client_type'],
                'expectedStatus' => 422,
            ],
            'status 422 with too long bank_account' => [
                'payload' => ['bank_account' => str_repeat('1', 256)],
                'expectedStatus' => 422,
            ],
            'status 422 with numeric name' => [
                'payload' => ['name' => 12345],
                'expectedStatus' => 422,
            ],
            'status 422 with numeric phone' => [
                'payload' => ['phone' => 123456789],
                'expectedStatus' => 422,
            ],
            'status 422 with numeric legal_form' => [
                'payload' => ['legal_form' => 12345],
                'expectedStatus' => 422,
            ],
            'status 422 with numeric bank_account' => [
                'payload' => ['bank_account' => 1234567890],
                'expectedStatus' => 422,
            ],
            'status 422 with empty legal_form' => [
                'payload' => ['legal_form' => ''],
                'expectedStatus' => 422,
            ],
            'status 422 with empty client_type' => [
                'payload' => ['client_type' => ''],
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
                    'client_id',
                    'name',
                    'phone',
                    'comments',
                    'bank_account',
                    'client_type',
                    'created',
                ]
            ],
            "errors",
            "status",
        ];
    }
}
