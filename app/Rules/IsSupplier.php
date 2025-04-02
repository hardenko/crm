<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Client;
use App\Enums\ClientTypeEnum;

final readonly class IsSupplier implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $client = Client::where('id', $value)
            ->where('client_type', ClientTypeEnum::SUPPLIER->value)
            ->exists();

        if (!$client) {
            $fail("The selected {$attribute} must be a valid supplier.");
        }
    }
}
