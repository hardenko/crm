<?php

namespace App\Http\Request;

use App\Rules\IsSupplier;
use Illuminate\Foundation\Http\FormRequest;

final class ComponentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'description' => [
                'nullable',
                'string'
            ],
            'supplier_id' => [
                'required',
                'integer',
                'exists:clients,id',
                new IsSupplier(),
            ],
        ];
    }
}
