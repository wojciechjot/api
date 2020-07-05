<?php

namespace App\Repositories\PostImage;

use App\PostImage;
use App\Repositories\AbstractBaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostImageRepository extends AbstractBaseRepository implements PostImageRepositoryInterface
{
    public function __construct(PostImage $model)
    {
        parent::__construct($model);
    }

    public function update(int $id, array $data): Model
    {
        $postImage = $this->find($id);
        $previousPath = $postImage->path;

        $postImage->update($data);

        Storage::delete($previousPath);

        return $postImage;
    }

    public function delete(int $id): void
    {
        $postImage = $this->find($id);

        Storage::delete($postImage->path);

        $postImage->delete();
    }
}
