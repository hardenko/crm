<?php

namespace App\Services;

use App\Dto\GetUserListDto;
use App\Interfaces\UserListServiceInterface;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

final class UserListService extends SearchQueryService implements UserListServiceInterface
{
    private const ITEMS_PER_PAGE = 20;

    public function getUserList(GetUserListDto $dto): LengthAwarePaginator
    {
        $query = User::query();

        $this->applyWhere(query: $query, field: 'name', value: $dto->name, operator: 'LIKE', wildcard: true);
        $this->applyWhere(query: $query, field: 'email', value: $dto->email, operator: 'LIKE', wildcard: true);

        return $query->paginate(self::ITEMS_PER_PAGE);
    }
}
