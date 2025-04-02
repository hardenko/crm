<?php

namespace Tests\Feature;

use Dotenv\Dotenv;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        if (file_exists(base_path('.env.testing'))) {
            Dotenv::createImmutable(base_path(), '.env.testing')->load();
        }
    }

    public function makeCall(string $uri, array $parameters = [], array $headers = []): TestResponse
    {
        $headers = array_merge(['Accept' => 'application/json'], $headers);

        return $this->json($this->getHttpMethod(), $uri, $parameters, $headers);
    }
}
