<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\UserController;
use Database\Seeders\UserSeeder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(UserController::class)]
final class UserControllerTest extends TestCase
{
    #[DataProvider('provideGetUserList')]
    public function testGetUserList($expectedStatus, $expectedResponse, $payload = []): void
    {
        $response = $this->makeCall(
            "/api/users",
            $payload,
        );

        $response->assertStatus($expectedStatus)
            ->assertJsonStructure($expectedResponse);
    }

    #[DataProvider('provideInvalidQuery')]
    public function testInvalidQuery($payload, $expectedStatus): void
    {
        $response = $this->makeCall(
            "/api/users",
            $payload,
        );

        $response->assertStatus($expectedStatus);
    }

    public static function provideGetUserList(): array
    {
        return [
            'get client list status 200 with name filters ' => [
                'expectedStatus' => 200,
                'expectedResponse' => self::successResponse(),
                'payload' => [
                    'name' => 'Manager'
                ],
            ],
            'get client list status 200 with email filters' => [
                'expectedStatus' => 200,
                'expectedResponse' => self::successResponse(),
                'payload' => [
                    'email' => 'wh_manager@example.com',
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
            'status 422 with numeric email' => [
                'payload' => ['email' => 123456789],
                'expectedStatus' => 422,
            ],
            'status 422 with wrong email form' => [
                'payload' => ['email' => 'example.com'],
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
                    'user_id',
                    'name',
                    'email',
                    'created_at',
                ]
            ],
            "errors",
            "status",
        ];
    }
}
