<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class ProductListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'bail',
                'string',
                'max:255',
            ],
            'price' => [
                'bail',
                'numeric',
                'min:0',
            ],
        ];
    }
}
