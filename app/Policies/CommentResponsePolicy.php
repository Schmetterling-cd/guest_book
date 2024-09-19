<?php

namespace App\Policies;

use App\Models\CommentResponse;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentResponsePolicy extends BasePolicy
{

    private const ROLE_MANAGER = 'manager';

    public function create(User $user): bool
    {

        return $user->hasRole(self::ROLE_MANAGER);

    }

    public function update(User $user, CommentResponse $commentResponse): bool
    {

        return ($user->id == $commentResponse->user_id) && $user->hasRole(self::ROLE_MANAGER);

    }

    public function delete(User $user, CommentResponse $commentResponse): bool
    {

        return ($user->id == $commentResponse->user_id) && $user->hasRole(self::ROLE_MANAGER);

    }

    public function get(User $user, CommentResponse $commentResponse): bool
    {

        return ($user->id == $commentResponse->user_id);

    }

}
