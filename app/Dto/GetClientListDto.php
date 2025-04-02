<?php

namespace App\Dto;

use App\Enums\ClientLegalFormEnum;
use App\Enums\ClientTypeEnum;

final class GetClientListDto extends BaseDto
{
    public function __construct(
        public ?string              $name,
        public ?string              $phone,
        public ?ClientLegalFormEnum $legal_form,
        public ?string              $bank_account,
        public ?ClientTypeEnum      $client_type,
    ){}

    public static function fromArray(array $params): self
    {
        return new self(
            name: $params['name'] ?? null,
            phone: $params['phone'] ?? null,
            legal_form: isset($params['legal_form']) ? ClientLegalFormEnum::from($params['legal_form']) : null,
            bank_account: $params['bank_account'] ?? null,
            client_type: isset($params['client_type']) ? ClientTypeEnum::from($params['client_type']) : null,
        );
    }
}
