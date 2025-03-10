<?php

namespace App\Dto;

final class GetComponentListDto extends BaseDto
{
    public function __construct(
        public ?string $supplier,
        public bool $quantity,
    ){}

    public static function fromArray(array $params): self
    {
        return new self(
            supplier: $params['supplier'] ?? null,
            quantity: $params['quantity'] ?? false,
        );
    }
}
