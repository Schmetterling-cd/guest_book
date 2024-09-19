<?php

namespace App\Services\Administration\Role;

use App\Contracts\Administration\Role\RoleInterface;
use App\Http\Resources\RoleResource;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class RoleService extends Service implements RoleInterface
{

    public function get(): JsonResource|bool
    {

        $model = $this->getModel(new Role());
        if (!$model) {
            return false;
        }

        $role = $model::with(['permissions'])->find($this->_data['roleId']);
        if (empty($role)) {
            $this->setError('Не удалось получить запись.');
            return false;
        }

        return new RoleResource($role);

    }

    public function add(): int|bool
    {

        $model = $this->getModel(new Role());
        if (!$model) {
            return false;
        }

        return DB::transaction(function () use ($model) {
            $role = $model::create([
                'name' => $this->_data['roleName']
            ]);

            $role->syncPermissions(Permission::whereIn('id', $this->_data['permissions'])->get());
            $role->refresh();

            return $role->id;
        });

    }

    public function update(): int|bool
    {

        $model = $this->getModel(new Role());
        if (!$model) {
            return false;
        }

        $role = $model::find($this->_data['roleId']);
        if (empty($role)) {
            $this->setError('Не удалось получить запись.');
            return false;
        }

        return DB::transaction(function () use ($role) {
            $role->name = $this->_data['roleName'];
            if ($role->isDirty()) {
                $role->save();
            }

            if ($role->permissions->diff($permissions = Permission::whereIn('id', $this->_data['permissions'])->get())) {
                $role->syncPermissions($permissions);
                $role->refresh();
            }

            return $role->id;
        });

    }

    public function delete(): bool
    {

        $model = $this->getModel(new Role());
        if (!$model) {
            return false;
        }

        $role = $model::find($this->_data['roleId']);
        if (empty($role)) {
            $this->setError('Не удалось получить запись.');
            return false;
        }

        return $role->roleHasPermission()->delete() && $role->delete();

    }

    public function getList(): AnonymousResourceCollection|bool
    {

        $model = $this->getModel(new Role());
        if (!$model) {
            return false;
        }

        $roles = $model::with(['permissions'])
            ->whereNot('name', '=', 'super-user')
            ->orderBy('id')
            ->paginate(50);

        if ($roles === null) {
            $this->setError('Не удалось получить список.');
            return false;
        }

        return RoleResource::collection($roles);

    }

    public function setRoleToUser(): bool
    {

        $model = $this->getModel(new Role());
        if (!$model) {
            return false;
        }

        $role = $model::find($this->_data['roleId']);

        $userModel = $this->getModel(new User());
        if (!$userModel) {
            return false;
        }

        $user = $userModel::find($this->_data['userId']);
        if (!$user->assignRole($role->name)) {
            $this->setError('Не удалось установить роль рользователю.');
            return false;
        }

        return true;

    }

}
