<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy extends BasePolicy
{

    public function update(User $user, Comment $comment): bool
    {
        return $user->id == $comment->user_id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->id == $comment->user_id;
    }

    public function get(User $user, Comment $comment): bool
    {

        return $user->id == $comment->user_id;

    }

}
