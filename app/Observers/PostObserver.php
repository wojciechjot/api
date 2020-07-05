<?php

namespace App\Observers;

use App\Post;
use Illuminate\Support\Facades\Storage;

class PostObserver
{
    public function deleting(Post $post): void
    {
        if($post->image()->getResults() !== null){
            Storage::delete($post->image()->getResults()->path);
        }
    }
}
