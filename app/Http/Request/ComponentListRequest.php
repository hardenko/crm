<?php

namespace App\Http\Request;

use App\Rules\IsSupplier;
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
                'required',
                'integer',
                'exists:clients,id',
                new IsSupplier(),
            ],
        ];
    }
}
