<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function create(array $data): Model;

    public function findAll(): Collection;

    public function findWithPaginate(
        ?int $perPage,
        ?int $page,
        ?string $sortParam,
        array $filterParams = []
    ): LengthAwarePaginator;

    public function getFilterOptions(): array;

    public function find(int $id): Model;

    public function update(int $id, array $data): Model;

    public function delete(int $id): void;
}
