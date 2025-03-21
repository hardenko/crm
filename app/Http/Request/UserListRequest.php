<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class UserListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'bail',
                'string',
                'max:255',
            ],
            'email' => [
                'bail',
                'string',
                'email',
            ],
        ];
    }
}
