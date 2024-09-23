<?php

namespace App\Services\Administration\Permission;

use App\Contracts\Administration\Permission\PermissionInterface;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Models\Role;
use App\Services\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionService extends Service implements PermissionInterface
{

    public function get(): JsonResource|bool
    {

        $model = $this->getModel(new Permission());
        if (!$model) {
            return false;
        }

        $permission = $model::find($this->_data['permissionId']);
        $a = Gate::inspect('get', $permission)->denied();
        if (Gate::inspect('get', $permission)->denied()) {
            $this->setError('Данная запись не доступна текущему пользователю.');
            return false;
        }

        return new PermissionResource($permission);

    }

    public function add(): int|bool
    {

        return DB::transaction(function () {
            $model = $this->getModel(new Permission());
            if (!$model) {
                return false;
            }

            $permission = $model::create([
                'name' => $this->_data['permissionName'],
            ]);

            $roleModel = $this->getModel(new Role());
            if (!$roleModel) {
                return false;
            }

            $superUserRole = $roleModel::where('name', '=', 'super-user')->first();
            $superUserRole->givePermissionTo($permission);

            return $permission->id;
        });

    }

    public function update(): int|bool
    {

        $model = $this->getModel(new Permission());
        if (!$model) {
            return false;
        }

        $permission = $model::find($this->_data['permissionId']);
        if (empty($permission)) {
            $this->setError('Не удалось получить запись.');
            return false;
        }

        if (Gate::inspect('update', $permission)->denied()) {
            $this->setError('Данная запись не доступна текущему пользователю.');
            return false;
        }

        $permission->name = $this->_data['permissionName'];
        if ($permission->isDirty()) {
            $permission->save();
        }

        return $permission->id;

    }

    public function delete(): bool
    {

        $model = $this->getModel(new Permission());
        if (!$model) {
            return false;
        }

        $permission = $model::find($this->_data['permissionId']);
        if (empty($permission)) {
            $this->setError('не удалось получить запись.');
            return false;
        }

        if (Gate::inspect('delete', $permission)->denied()) {
            $this->setError('Данная запись не доступна текущему пользователю.');
            return false;
        }

        return $permission->delete();

    }

    public function getList(): AnonymousResourceCollection|bool
    {

        $model = $this->getModel(new Permission());
        if (!$model) {
            return false;
        }

        $permissions = $model->orderBy('id')->paginate(50);
        if ($permissions === false) {
            $this->setError('не удалосб получить список привелегий.');
            return false;
        }

        return PermissionResource::collection($permissions);

    }
}
