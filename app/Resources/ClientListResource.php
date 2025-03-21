<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ClientListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'client_id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'comments' => $this->comments,
            'legal_form' => $this->legal_form->label(),
            'bank_account' => $this->bank_account,
            'client_type' => $this->client_type->label(),
            'created' => $this->created_at->toDateTimeString(),
        ];
    }
}
