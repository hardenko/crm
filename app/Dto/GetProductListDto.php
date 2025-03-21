<?php

namespace App\Dto;

final class GetProductListDto extends BaseDto
{
    public function __construct(
        public ?string $name,
        public ?float $price,
    ){}

    public static function fromArray(array $params): self
    {
        return new self(
            name: $params['name'] ?? null,
            price: $params['price'] ?? null,
        );
    }
}
