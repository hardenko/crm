<?php

namespace App\Services;

use App\Dto\GetClientListDto;
use App\Interfaces\ClientListServiceInterface;
use App\Models\Client;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class ClientListService extends SearchQueryService implements ClientListServiceInterface
{
    private const ITEMS_PER_PAGE = 20;

    public function getClientList(GetClientListDto $dto): LengthAwarePaginator
    {
        $query = Client::query();

        $this->applyWhere(query: $query, field: 'name', value: $dto->name, operator: 'LIKE', wildcard: true);
        $this->applyWhere(query: $query, field: 'phone', value: $dto->phone, operator: 'LIKE', wildcard: true);
        $this->applyWhere(query: $query, field: 'legal_form', value: $dto->legal_form);
        $this->applyWhere(query: $query, field: 'bank_account', value: $dto->bank_account, operator: 'LIKE', wildcard: true);
        $this->applyWhere(query: $query, field: 'client_type', value: $dto->client_type);

        return $query->paginate(self::ITEMS_PER_PAGE);
    }
}
