<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractBaseRepository implements BaseRepositoryInterface
{
    use BaseFilterTrait, BaseSortTrait;

    const PER_PAGE = 10;
    const PAGE = 1;

    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $data): Model
    {
        return $this->model::create($data);
    }

    public function findAll(): Collection
    {
        return $this->model::all();
    }

    public function findWithPaginate(
        ?int $perPage,
        ?int $page,
        ?string $sortParam ,
        array $filterParams = []
    ): LengthAwarePaginator
    {
        $perPage = $perPage ? $perPage : self::PER_PAGE;
        $page = $page ? $page : self::PAGE;

        $query = $this->model::query();

        $query = $this->applyFiltersIfRequired($query, $filterParams);

        $query = $this->applySortIfRequired($query, $sortParam);

        return $query->paginate($perPage, '*', 'page', $page);
    }

    public function getFilterOptions(): array
    {
        return [];
    }

    public function find(int $id): Model
    {
        return $this->model::where('id', $id)->firstOrFail();
    }

    public function update(int $id, array $data): Model
    {
        $object = $this->find($id);

        $object->update($data);

        return $object;
    }

    public function delete(int $id): void
    {
        $this->find($id)->delete();
    }
}
