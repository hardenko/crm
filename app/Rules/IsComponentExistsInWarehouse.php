<?php

namespace App\Rules;

use App\Models\Warehouse;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final readonly class IsComponentExistsInWarehouse implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = Warehouse::where('component_id', $value)->exists();

        if (!$exists) {
            $fail("The selected component is not available in warehouse.");
        }
    }
}
