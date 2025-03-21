<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class UserListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'user_id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created' => $this->created_at->toDateTimeString(),
        ];
    }
}
