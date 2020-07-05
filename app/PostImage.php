<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;

/**
 * Fields:
 * path - string
 * post_id(post) - relation(*One to One)
 */
class PostImage extends Model
{
    protected $fillable = [
        'post_id',
        'path',
    ];

    public $timestamps = false;

    public function setPathAttribute(UploadedFile $file): void
    {
        $this->attributes['path'] = $file->store(config('services.images_path'));
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
