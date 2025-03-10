<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ComponentListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'component_id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity_in_stock,
            'supplier' => $this->supplier,
            'created' => $this->created_at,
        ];
    }
}
