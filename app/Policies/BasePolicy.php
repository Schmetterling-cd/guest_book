<?php

namespace App\Policies;

use App\Models\User;

class BasePolicy
{

    protected const ROLE_SUPER_ADMIN = 'super-admin';

    public function before(User $user)
    {

        return $user->hasRole(self::ROLE_SUPER_ADMIN);

    }

}
