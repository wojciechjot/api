<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait BaseFilterTrait
{
    protected Builder $query;

    protected string $field;

    protected string $suffix;

    protected string $filterParam;

    public function applyFiltersIfRequired(Builder $query, array $filterParams): Builder
    {
        $this->query = $query;

        foreach ($filterParams as $key => $filterParam) {
            $paramArray = explode('__', $key);

            $this->field =  $paramArray[0];
            $this->suffix = $paramArray[1] ?? '';
            $this->filterParam = $filterParam;

            $this->applyGreaterThanFilter();
            $this->applyGreaterEqualThanFilter();

            $this->applyLessThanFilter();
            $this->applyLessEqualThanFilter();

            $this->applyLikeFilter();

            $this->applyEqualFilter();

            $this->applyDateGreaterThanFilter();
            $this->applyDateGreaterEqualThanFilter();

            $this->applyDateLessThanFilter();
            $this->applyDateLessEqualThanFilter();

            $this->applyDateEqualFilter();
        }

        return $this->query;
    }

    private function applyGreaterThanFilter(): void
    {
        if ($this->suffix === 'gt') {
            $this->query->where($this->field, '>' ,  $this->filterParam);
        }
    }

    private function applyGreaterEqualThanFilter(): void
    {
        if ($this->suffix === 'get') {
            $this->query->where($this->field, '>=' ,  $this->filterParam);
        }
    }

    private function applyLessThanFilter(): void
    {
        if ($this->suffix === 'lt') {
            $this->query->where($this->field, '<' ,  $this->filterParam);
        }
    }

    private function applyLessEqualThanFilter(): void
    {
        if ($this->suffix === 'let') {
            $this->query->where($this->field, '<=' ,  $this->filterParam);
        }
    }

    private function applyLikeFilter(): void
    {
        if ($this->suffix === 'l') {
            $this->query->where($this->field, 'like' ,  '%'. $this->filterParam .'%');
        }
    }

    private function applyEqualFilter(): void
    {
        if ($this->suffix === '') {
            $this->query->where($this->field, '=' , $this->filterParam);
        }
    }

    private function applyDateGreaterThanFilter(): void
    {
        if ($this->suffix === 'date_gt') {
            $date = Carbon::parse($this->filterParam);

            $this->query->whereDate($this->field, '>' , $date);
        }
    }

    private function applyDateGreaterEqualThanFilter(): void
    {
        if ($this->suffix === 'date_get') {
            $date = Carbon::parse($this->filterParam);

            $this->query->whereDate($this->field, '>=' , $date);
        }
    }

    private function applyDateLessThanFilter(): void
    {
        if ($this->suffix === 'date_lt') {
            $date = Carbon::parse($this->filterParam);

            $this->query->whereDate($this->field, '<' , $date);
        }
    }

    private function applyDateLessEqualThanFilter(): void
    {
        if ($this->suffix === 'date_let') {
            $date = Carbon::parse($this->filterParam);

            $this->query->whereDate($this->field, '<=' , $date);
        }
    }

    private function applyDateEqualFilter(): void
    {
        if ($this->suffix === 'date') {
            $date = Carbon::parse($this->filterParam);

            $this->query->whereDate($this->field, '=' , $date);
        }
    }

    protected static function getBaseFilterOptions (array $baseFields): array
    {
        $baseFilters = [];

        foreach ($baseFields as $baseItem) {
            $baseFilters[] = $baseItem;
            $baseFilters[] = $baseItem . '__l';
            $baseFilters[] = $baseItem . '__get';
            $baseFilters[] = $baseItem . '__gt';
            $baseFilters[] = $baseItem . '__let';
            $baseFilters[] = $baseItem . '__lt';
        }

        return $baseFilters;
    }

    protected static function getDateFilterOptions (array $dateFields): array
    {
        $dateFilters = [];

        foreach ($dateFields as $dateItem) {
            $dateFilters[] = $dateItem . '__date';
            $dateFilters[] = $dateItem . '__date_get';
            $dateFilters[] = $dateItem . '__date_gt';
            $dateFilters[] = $dateItem . '__date_let';
            $dateFilters[] = $dateItem . '__date_lt';
        }
        return $dateFilters;
    }
}
