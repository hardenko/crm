<?php

namespace App\Services;

use App\Dto\GetComponentListDto;
use App\Interfaces\ComponentListServiceInterface;
use App\Models\Component;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class ComponentListService implements ComponentListServiceInterface
{
    private const ITEMS_PER_PAGE = 20;

    public function getComponentList(GetComponentListDto $dto): LengthAwarePaginator
    {
        $query = Component::query();

        if (!empty($dto->supplier)) {
            $query->where('supplier', $dto->supplier);
        }

        if ($dto->quantity) {
            $query->where('quantity_in_stock', '>', 0);
        }

        return $query->paginate(self::ITEMS_PER_PAGE);
    }
}
