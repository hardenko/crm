<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ProductListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'product_id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'price' => $this->price,
            'components' => ComponentResource::collection($this->whenLoaded('componentsRelation')),
            'created' => $this->created_at->toDateTimeString(),
        ];
    }
}
