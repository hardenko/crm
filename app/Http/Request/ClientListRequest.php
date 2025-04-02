<?php

namespace App\Http\Request;

use App\Enums\ClientLegalFormEnum;
use App\Enums\ClientTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ClientListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'bail',
                'string',
                'max:255',
            ],
            'phone' => [
                'bail',
                'string',
            ],
            'legal_form' => [
                'bail',
                'string',
                Rule::enum(ClientLegalFormEnum::class),
            ],
            'bank_account' => [
                'bail',
                'string',
                'max:255',
            ],
            'client_type' => [
                'bail',
                'string',
                Rule::enum(ClientTypeEnum::class),
            ]
        ];
    }
}
