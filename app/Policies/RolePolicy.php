<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy extends BasePolicy
{

    public const PERMISSION_GET = 'getRole';
    public const PERMISSION_ADD = 'addRole';
    public const PERMISSION_UPDATE = 'updateRole';
    public const PERMISSION_DELETE = 'deleteRole';
    public const PERMISSION_GET_LIST = 'getRoleList';
    public const PERMISSION_SET_ROLE = 'setUserRole';

}
