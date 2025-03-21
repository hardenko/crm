<?php

namespace App\Services;

use App\Dto\GetProductListDto;
use App\Interfaces\ProductListServiceInterface;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class ProductListService extends SearchQueryService implements ProductListServiceInterface
{
    private const ITEMS_PER_PAGE = 20;

    public function getProductList(GetProductListDto $dto): LengthAwarePaginator
    {
        $query = Product::query()->with(['belongsToManyComponents']);

        $this->applyWhere(query: $query, field: 'name', value: $dto->name, operator: 'LIKE', wildcard: true);
        $this->applyWhere(query: $query, field: 'price', value: $dto->price);

        return $query->paginate(self::ITEMS_PER_PAGE);
    }
}
