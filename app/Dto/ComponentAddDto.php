<?php

namespace App\Dto;

final class ComponentAddDto extends BaseDto
{
    public function __construct(
        public string $name,
        public ?string $description,
        public int $supplier_id,
    ) {}

    public static function fromArray(array $params): self
    {
        return new self(
            name: $params['name'],
            description: $params['description'] ?? null,
            supplier_id: $params['supplier_id'],
        );
    }
}
