<?php

namespace App\Policies;

use App\Http\Resources\User\Side\UserResource as SideUserResource;
use App\Http\Resources\User\Owner\UserResource as OwnerUserResource;
use App\Models\User;

class UserPolicy extends BasePolicy
{

	public const PERMISSION_GET = 'getUser';
	public const PERMISSION_ADD = 'addUser';
	public const PERMISSION_UPDATE = 'updateUser';
	public const PERMISSION_DELETE = 'deleteUser';
	public const PERMISSION_GET_LIST = 'getUserList';

	public function get(User $currentUser, $user): bool
	{

		return $currentUser->id === $user->id;

	}

	public function add(User $user): bool
	{

		return $user->hasRole(static::ROLE_SUPER_ADMIN);

	}

	public function delete(User $currentUser, $user): bool
	{

		return $currentUser->id === $user->id;

	}

	public function update(User $currentUser, $user): bool
	{

		return $currentUser->id === $user->id;

	}

	public function getList(User $user): bool
	{

		return $user->hasRole(static::ROLE_SUPER_ADMIN);

	}

}
