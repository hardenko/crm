<?php

namespace App\Dto;

final class GetComponentListDto extends BaseDto
{
    public function __construct(
        public ?string $name,
        public int $supplier_id,
    ){}

    public static function fromArray(array $params): self
    {
        return new self(
            name: $params['name'] ?? null,
            supplier_id: $params['supplier_id'] ?? 0,
        );
    }
}
