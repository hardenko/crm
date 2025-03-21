<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

readonly class SearchQueryService
{
    public function applyWhere(Builder $query, string $field, mixed $value, string $operator = '=', bool $wildcard = false): void
    {    if (!empty($value)) {
        $query->where($field, $operator, $wildcard ? "%$value%" : $value);    }
    }
}
