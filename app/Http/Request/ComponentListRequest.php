<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class ComponentListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'bail',
                'string',
                'max:255',
            ],
            'supplier_id' => [
                'bail',
                'integer',
                'exists:components,supplier_id',
            ],
        ];
    }
}
