<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

trait BaseSortTrait
{
    protected function applySortIfRequired(Builder $query, ?string $sortParam): Builder
    {
        if ($sortParam !== null) {
            $sortParamArray = explode('.', $sortParam);
            $sortBy = $sortParamArray[0];
            $sortType = $sortParamArray[1];

            if ($this->isSortable($sortBy, $sortType)) {
                $query->orderBy($sortBy, $sortType);
            }
        }

        return $query;
    }

    protected function isSortable(string $sortBy, string $sortType): bool
    {
        return in_array($sortBy, $this->getSortableFields())
            && ($sortType === 'asc' || $sortType === 'desc');
    }

    protected function getSortableFields(): array
    {
        return [];
    }
}
