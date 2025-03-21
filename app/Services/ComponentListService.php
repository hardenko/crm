<?php

namespace App\Services;

use App\Dto\GetComponentListDto;
use App\Interfaces\ComponentListServiceInterface;
use App\Models\Component;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class ComponentListService extends SearchQueryService implements ComponentListServiceInterface
{
    private const ITEMS_PER_PAGE = 20;

    public function getComponentList(GetComponentListDto $dto): LengthAwarePaginator
    {
        $query = Component::query();

        $this->applyWhere(query: $query, field: 'name', value: $dto->name, operator: 'LIKE', wildcard: true);
        $this->applyWhere(query: $query, field: 'supplier_id', value: $dto->supplier_id);

        return $query->paginate(self::ITEMS_PER_PAGE);
    }
}
