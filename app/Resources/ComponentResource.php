<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ComponentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'component_id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'supplier_id' => $this->supplier_id,
            'quantity' => $this->whenPivotLoaded('product_components', function () {
                return $this->pivot->quantity;
            }),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
