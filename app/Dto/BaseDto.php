<?php

namespace App\Dto;

use App\Helpers\ConvertArrayKeys;

abstract class BaseDto
{
    /**
     * @param array<mixed, mixed> $params
     */
    abstract public static function fromArray(array $params): self;

    public function toArray(): array
    {
        $properties = get_object_vars($this);

        return ConvertArrayKeys::toSnakeCase($properties);
    }
}
