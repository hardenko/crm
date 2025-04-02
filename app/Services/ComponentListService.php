<?php

namespace App\Services;

use App\Dto\ComponentAddDto;
use App\Dto\GetComponentListDto;
use App\Interfaces\ComponentListServiceInterface;
use App\Models\Component;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class ComponentListService implements ComponentListServiceInterface
{
    private const ITEMS_PER_PAGE = 20;

    public function getComponentList(GetComponentListDto $dto): LengthAwarePaginator
    {
        return Component::query()
            ->where('name', 'LIKE', $dto->name)
            ->where('supplier_id', $dto->supplier_id)
            ->paginate(self::ITEMS_PER_PAGE);
    }

    public function createComponent(ComponentAddDto $dto): Component
    {
        return Component::create($dto->toArray());
    }
}
