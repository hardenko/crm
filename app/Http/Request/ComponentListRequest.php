<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class ComponentListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'supplier' => [
                'bail',
                'string',
                'max:255',
                'exists:components,supplier',
            ],
            'quantity' => [
                'bail',
                'boolean',
            ],
        ];
    }
}
