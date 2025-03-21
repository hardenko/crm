<?php

namespace App\Dto;

final class GetUserListDto extends BaseDto
{
    public function __construct(
        public ?string $name,
        public ?string $email,
    ){}

    public static function fromArray(array $params): self
    {
        return new self(
            name: $params['name'] ?? null,
            email: $params['email'] ?? null,
        );
    }
}
