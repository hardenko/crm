<?php

namespace App\Dto;

use App\Enums\ClientLegalForm;
use App\Enums\ClientType;

final class GetClientListDto extends BaseDto
{
    public function __construct(
        public ?string $name,
        public ?string $phone,
        public ?ClientLegalForm $legal_form,
        public ?string $bank_account,
        public ?ClientType $client_type,
    ){}

    public static function fromArray(array $params): self
    {
        return new self(
            name: $params['name'] ?? null,
            phone: $params['phone'] ?? null,
            legal_form: isset($params['legal_form']) ? ClientLegalForm::from($params['legal_form']) : null,
            bank_account: $params['bank_account'] ?? null,
            client_type: isset($params['client_type']) ? ClientType::from($params['client_type']) : null,
        );
    }
}
