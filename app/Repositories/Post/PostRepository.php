<?php

namespace App\Repositories\Post;

use App\Post;
use App\Repositories\AbstractBaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostRepository extends AbstractBaseRepository implements PostRepositoryInterface
{
    public function __construct(Post $model)
    {
        parent::__construct($model);
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

        $query->with('image');

        return $query->paginate($perPage, '*', 'page', $page);
    }

    public function findWithPaginateByUser(
        ?int $perPage,
        ?int $page,
        ?string $sortParam ,
        int $userId,
        array $filterParams = []
    ): LengthAwarePaginator
    {
        $perPage = $perPage ? $perPage : self::PER_PAGE;
        $page = $page ? $page : self::PAGE;

        $query = $this->model::query();

        $query->where('user_id', $userId);

        $query = $this->applyFiltersIfRequired($query, $filterParams);

        $query = $this->applySortIfRequired($query, $sortParam);

        $query->with('image');

        return $query->paginate($perPage, '*', 'page', $page);
    }

    public function getFilterOptions(): array
    {
        $baseFilters = self::getBaseFilterOptions([
            'id',
            'title',
            'content',
        ]);

        $dateFilters = self::getDateFilterOptions([
            'publication_date',
            'beginning',
            'end'
        ]);

        return array_merge($baseFilters, $dateFilters);
    }

    protected function getSortableFields(): array
    {
        return [
            'id',
            'title',
            'content',
            'publication_date',
            'beginning',
            'end'
        ];
    }
}
