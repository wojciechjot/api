<?php

namespace App\Policies;

use App\PostImage;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostImagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\PostImage  $postImage
     * @return bool
     */
    public function view(User $user, PostImage $postImage): bool
    {
        return $user->id === $postImage->post()->first()->user_id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\PostImage  $postImage
     * @return bool
     */
    public function update(User $user, PostImage $postImage): bool
    {
        return $user->id === $postImage->post()->first()->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\PostImage  $postImage
     * @return bool
     */
    public function delete(User $user, PostImage $postImage): bool
    {
        return $user->id === $postImage->post()->first()->user_id;
    }
}
