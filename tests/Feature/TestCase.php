<?php

namespace Tests\Feature;

use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, WithFaker;

    public function makeCall(string $uri, array $parameters = [], array $headers = []): TestResponse
    {
        $headers = array_merge(['Accept' => 'application/json'], $headers);

        return $this->json($this->getHttpMethod(), $uri, $parameters, $headers);
    }
}
