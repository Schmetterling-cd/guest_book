<?php

namespace App\Services\Administration\User;

use App\Contracts\Administration\User\UserInterface;
use App\Contracts\Common\ServiceInterface;
use App\Http\Resources\IdResource;
use App\Http\Resources\User\Owner\UserResource as OwnerUserResource;
use App\Http\Resources\User\Side\UserResource as SideUserResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;


class UserService extends Service implements UserInterface, ServiceInterface
{

	public function get(): bool|JsonResource
	{

		$model = $this->getModel(new User());
		if (!$model) {
			return false;
		}

		$user = User::whith('roles')->find($this->_data['userId']);

		return Gate::inspect('get', $user)->allowed() ? new OwnerUserResource($user) : new SideUserResource($user);

	}

	public function add(): bool|IdResource
	{

		$model = $this->getModel(new User());
		if (!$model) {
			return false;
		}

		$user = $model::create([
			'login' => $this->_data['userLogin'],
			'password' => Hash::make($this->_data['userPassword']),
		]);

		if (empty($user)) {
			$this->setError('Не удалось создать пользователя.');
			return false;
		}

		return new IdResource($user);

	}

	public function update(): bool|IdResource
	{

		$model = $this->getModel(new User());
		if (!$model) {
			return false;
		}

		$user = $model::find($this->_data['userId']);

		if (Gate::inspect('update', $user)->denied()) {
			$this->setError('Нет прав для изменения пользователя.');
			return false;
		}

		$user->login = $this->_data['userLogin'];
		$user->password = $this->_data['userPassword'];

		if ($user->isDirty()) {
			$user->save();
		}

		return new IdResource($user);

	}

	public function delete(): bool
	{

		$model = $this->getModel(new User());
		if (!$model) {
			return false;
		}

		$user = $model::find($this->_data['userId']);

		if (Gate::inspect('delete', $user)->denied()) {
			$this->setError('Нет прав для удаления данного пользлвателя');
			return false;
		}

		return $user->delete();

	}

	public function getList(): bool|AnonymousResourceCollection
	{

		$model = $this->getModel(new User());
		if (!$model) {
			return false;
		}

		$isAllowed = Gate::inspect('getList')->allowed();

		if ($isAllowed) {
			$list = $model::with('roles')->paginate(50);
		} else {
			$list = $model::paginate(50);
		}

		if (empty($list)) {
			$this->setError('Не удалось получить список.');
			return false;
		}

		return $isAllowed ? OwnerUserResource::collection($list) : SideUserResource::collection($list);

	}

}
