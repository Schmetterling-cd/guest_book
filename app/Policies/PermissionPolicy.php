<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class PermissionPolicy extends BasePolicy
{

    public const PERMISSION_GET = 'getPermission';
    public const PERMISSION_ADD = 'addPermission';
    public const PERMISSION_UPDATE = 'updatePermission';
    public const PERMISSION_DELETE = 'deletePermission';
    public const PERMISSION_GET_LIST = 'getPermissionList';

    public function update(User $user, Permission $permission): bool
    {

        return $user->id == $permission->user_id;

    }

    public function delete(User $user, Permission $permission): bool
    {

        return $user->id == $permission->user_id;

    }

    public function get(User $user, Permission $permission): bool
    {

        return $user->id == $permission->user_id;

    }

}
