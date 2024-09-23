<?php

namespace App\Http\Controllers\Administration;

use App\Contracts\Administration\Permission\PermissionInterface;
use App\Contracts\Administration\Role\RoleInterface;
use App\Contracts\Administration\User\UserInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Models\User;
use App\Policies\RolePolicy;
use App\Rules\LoginUpdateRule;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;


class AdministrationController extends Controller
{

    public function getRoleList(ListRequest $request, RoleInterface $service, User $user): array
    {

        $service->setData(
            $request->validated()
        );

        $list = $service->getList();
        if (empty($list)) {
            return $service->getApiError();
        }

        return $service->getApiResponse($list);

    }

    public function getRole(Request $request, RoleInterface $service, User $user): array
    {

        $user->hasPermissionTo(RolePolicy::PERMISSION_GET);

        $service->setData(
            $request->validate([
                'roleId' => ['required', 'string', 'exists:role,id'],
            ])
        );

        $role = $service->get();
        if (empty($role)) {
            return $service->getApiError();
        }

        return $service->getApiResponse($role);

    }

    public function addRole(Request $request, RoleInterface $service, User $user): array
    {

        $user->hasPermissionTo(RolePolicy::PERMISSION_ADD);

        $service->setData(
            $request->validate([
                'roleName' => ['required', 'string', 'min:5', 'max:255', 'unique:permissions,name'],
                'permissions' => ['required', 'array'],
                'permissions.*' => ['required', 'string', 'exists:permissions,id'],
            ])
        );

        $roleId = $service->add();
        if (empty($roleId)) {
            return $service->getApiError();
        }

        return $service->getApiResponse(['roleId' => $roleId]);

    }

    public function updateRole(Request $request, RoleInterface $service, User $user): array
    {

        $user->hasPermissionTo(RolePolicy::PERMISSION_UPDATE);

        $service->setData(
            $request->validate([
                'roleId' => ['required', 'string'],
                'roleName' => ['required', 'string', 'min:5', 'max:255', Rule::notIn(['super-user'])],
                'permissions' => ['required', 'array'],
                'permissions.*' => ['required', 'string', 'exists:permissions,id'],
            ])
        );

        $roleId = $service->update();
        if (empty($roleId)) {
            return $service->getApiError();
        }

        return $service->getApiResponse(['roleId' => $roleId]);

    }

    public function deleteRole(Request $request, RoleInterface $service, User $user): array
    {

        $user->hasPermissionTo(RolePolicy::PERMISSION_DELETE);

        $service->setData(
            $request->validate([
                'roleId' => ['required', 'string', 'exists:role,id'],
            ])
        );

        if ($service->delete()) {
            return $service->getApiResponse();
        }

        return $service->getApiError();

    }

    public function getPermissionList(ListRequest $request, PermissionInterface $service): array
    {

        $service->setData($request->validated());

        $permissions = $service->getList();
        if ($permissions === false) {
            return $service->getApiError();
        }

        return $service->getApiResponse($permissions);

    }

    public function addPermission(Request $request, PermissionInterface $service): array
    {

        $service->setData(
            $request->validate([
                'permissionName' => ['required', 'string', 'min:1', 'max:255'],
            ])
        );

        $permission = $service->add();
        if (empty($permission)) {
            return $service->getApiError();
        }

        return $service->getApiresponse($permission);

    }

    public function updatePermission(Request $request, PermissionInterface $service): array
    {

        $service->setData(
            $request->validate([
                'permissionId' => ['required', 'string'],
                'permissionName' => ['required', 'string', 'min:1', 'max:255'],
            ])
        );

        $permissionId = $service->update();
        if (empty($permissionId)) {
            return $service->getApiError();
        }

        return $service->getApiResponse(['permissionId' => $permissionId]);

    }

    public function getPermission(Request $request, PermissionInterface $service): array
    {

        $service->setData(
            $request->validate([
                'permissionId' => ['required', 'string'],
            ])
        );

        $permissionId = $service->get();
        if (empty($permissionId)) {
            return $service->getApiError();
        }

        return $service->getApiResponse(['permissionId' => $permissionId]);

    }

    public function deletePermission(Request $request, PermissionInterface $service): array
    {

        $service->setData(
            $request->validate([
                'permissionId' => ['required', 'string'],
            ])
        );

        if ($service->delete()) {
            return $service->getApiResponse();
        }

        return $service->getApiError();

    }

    public function setUserRole(Request $request, RoleInterface $service): array
    {

        $service->setData(
            $request->validate([
                'userId' => ['required', 'string', 'exists:users,id'],
                'roleId' => ['required', 'string', 'exists:roles,id'],
            ])
        );

        if ($service->setRoleToUser()) {
            return $service->getApiResponse();
        }

        return $service->getApiError();

    }

}
