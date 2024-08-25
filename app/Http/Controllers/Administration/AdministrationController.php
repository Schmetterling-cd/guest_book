<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Throwable;


class AdministrationController extends Controller
{

    public function getRoleList(): array
    {

        try {
            return $this->getApiResponse(Role::select('id as roleId', 'name as roleName', 'guard_name as guardName')
                ->whereNot('name', '=', 'super-user')
                ->orderBy('id')
                ->paginate(50));
        } catch (Throwable $exception) {
            throw new Exception('Ну удалось получить список.', 500);
        }

    }

    public function getRole(Request $request)
    {

        try {
            $validated = $request->validate([
                'roleId' => ['required', 'string', 'exists:role,id'],
            ]);

            $role = Role::select('id as roleId', 'name as roleName', 'guard_name as guardName')->findById($validated['roleId']);

            $role->permissions = DB::table('role_has_permissions')
                ->select('permissions.id as permissionId', 'permissions.name as permissionName', 'permissions.guard_name as guardName')
                ->rightJoin('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->get();

            return $role;
        } catch (Throwable $exception) {
            throw new Exception('Не удалось получить запись.', 500);
        }

    }

    public function addRole(Request $request): array
    {

        try {
            $validated = $request->validate([
                'roleName' => ['required', 'string', 'min:5', 'max:255', 'unique:permissions,name'],
                'permissions' => ['required', 'array'],
                'permissions.*' => ['required', 'string', 'exists:permissions,id'],
            ]);

            $newRole = DB::transaction(function () use ($validated) {
                $newRole = Role::create([
                    'name' => $validated['roleName'],
                ]);

                $permissions = Permission::whereIn('id', $validated['permissions']);
                $newRole->syncPermissions($permissions);

                return $newRole;
            });

            return ['roleId' => $newRole->id];
        } catch (Throwable $exception) {
            throw new Exception('Не удалось добавить запись.', 500);
        }

    }

    public function updateRole(Request $request): array
    {

        try {
            $validated = $request->validate([
                'roleId' => ['required', 'string'],
                'roleName' => ['required', 'string', 'min:5', 'max:255', Rule::notIn(['super-user'])],
                'permissions' => ['required', 'array'],
                'permissions.*' => ['required', 'string', 'exists:permissions,id'],
            ]);

            $role = DB::transaction(function () use ($validated) {
                $role = Role::findById($validated['roleId']);

                $role->name = $validated['roleName'];
                if ($role->isDirty()) {
                    $role->save();
                }

                $permissions = Permission::whereIn('id', $validated['permissions'])->get();
                $role->syncPermissions($permissions);

                return $role;
            });

            return ['roleId' => $role->id];
        } catch (Throwable $exception) {
            throw new Exception('Не удалось изменить запись.', 500);
        }

    }

    public function deleteRole(Request $request)
    {

        try {
            $validated = $request->validate([
                'roleId' => ['required', 'string', 'exists:role,id'],
            ]);

            return DB::transaction(function () use ($validated) {

                $roleHasPermissions = DB::table('role_has_permissions')
                    ->where('role_id', '=', $validated['roleId']);

                $role = Role::select('id as roleId', 'name as roleName', 'guard_name as guardName')->findById($validated['roleId']);

                return $roleHasPermissions->delete() && $role->delete();

            });
        } catch (Throwable $exception) {
            throw new Exception('Не удалось удалить запись.', 500);
        }

    }

    public function getPermissionList(): array
    {

        try {
            return $this->getApiResponse(Permission::select('id as permissionId', 'name as permissionName', 'guard_name as guardName')->orderBy('id')->paginate(50));
        } catch (Throwable $exception) {
            throw new Exception('Ну удалось получить список.', 500);
        }

    }

    public function addPermission(Request $request): array
    {

        try {
            $validated = $request->validate([
                'permissionName' => ['required', 'string', 'min:1', 'max:255'],
            ]);

            $permission = DB::transaction(function () use ($validated) {
                $permission = Permission::create(['name' => $validated['permissionName']]);

                $superUserRole = Role::where('name', '=', 'super-user')->first();
                $superUserRole->givePermissionTo($permission);

                return $permission;
            });

            return $this->getApiResponse(['permissionId' => $permission->id]);
        } catch (Throwable $exception) {
            throw new Exception('Не удалось добавить запись.', 500);
        }

    }

    public function updatePermission(Request $request): array
    {

        try {
            $validated = $request->validate([
                'permissionId' => ['required', 'string'],
                'permissionName' => ['required', 'string', 'min:1', 'max:255'],
            ]);

            $permission = Permission::findById($validated['permissionId']);

            $permission->name = $validated['permissionName'];

            if ($permission->isDirty()) {
                $permission->save();
            }

            return $this->getApiResponse(['permissionId' => $validated['permissionId']]);
        } catch (Throwable $exception) {
            throw new Exception('Не удалось изменить запись.', 500);
        }

    }

    public function getPermission(Request $request): array
    {

        try {
            $validated = $request->validate([
                'permissionId' => ['required', 'string'],
            ]);

            $permission = Permission::findById($validated['permissionId']);

            return $this->getApiResponse(['permissionId' => $permission->id, 'permissionName' => $permission->name, 'guardName' => $permission->guard_name]);
        } catch (Throwable $exception) {
            throw new Exception('Не удалось получить запись.', 500);
        }

    }

    public function setUserRole(Request $request)
    {

        try {
            $validated = $request->validate([
                'userId' => ['required', 'string', 'exists:users,id'],
                'roleId' => ['required', 'string', 'exists:roles,id'],
            ]);

            $role = Role::findById($validated['roleId']);
            $user = User::find($validated['userId']);
            $user->assignRole($role->name);

            return $this->getApiResponse(true);
        } catch (Throwable $exception) {
            throw new Exception('Не удалось назначить роль пользователю.', 500);
        }

    }

}
