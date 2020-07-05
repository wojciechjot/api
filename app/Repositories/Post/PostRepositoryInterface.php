<?php

namespace App\Repositories\Post;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface extends BaseRepositoryInterface
{
    public function findWithPaginateByUser(
        ?int $perPage,
        ?int $page,
        ?string $sortParam,
        int $userId,
        array $filterParams = []
    ): LengthAwarePaginator;
}
