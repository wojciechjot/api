<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Fields:
 * title - string
 * content - string
 * publication_date - date
 * beginning - date
 * end - date
 * image - relation(One to One*)
 */
class Post extends Model
{

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'publication_date',
        'beginning',
        'end'
    ];

    public function setPublicationDateAttribute(string $value): void
    {
        $this->attributes['publication_date'] = (new Carbon($value))->format('Y-m-d');
    }

    public function getPublicationDateAttribute(string $value): string
    {
        return (new Carbon($value))->format('Y-m-d');
    }

    public function setBeginningAttribute(string $value): void
    {
        $this->attributes['beginning'] = (new Carbon($value))->format('Y-m-d');
    }

    public function getBeginningAttribute(string $value): string
    {
        return (new Carbon($value))->format('Y-m-d');
    }

    public function setEndAttribute(string $value): void
    {
        $this->attributes['end'] = (new Carbon($value))->format('Y-m-d');
    }

    public function getEndAttribute(string $value): string
    {
        return (new Carbon($value))->format('Y-m-d');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function image(): HasOne
    {
        return $this->hasOne(PostImage::class);
    }
}
