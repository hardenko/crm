<?php

namespace App\Rules;

use Closure;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Contracts\Validation\ValidationRule;

final readonly class ValidateStock implements ValidationRule
{
    protected int $productId;

    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $product = Product::with('componentsRelation')->find($this->productId);

        if (!$product) {
            $fail('Product not found.');
            return;
        }

        foreach ($product->componentsRelation as $component) {
            $needed = $component->pivot->quantity * $value;
            $available = Warehouse::where('component_id', $component->id)->value('quantity');

            if ($available === null || $available < $needed) {
                $fail("Not enough stock for component: $component->name. Required: $needed, Available: " . ($available ?? 0));
            }
        }
    }
}
