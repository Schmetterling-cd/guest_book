<?php

use App\Http\Controllers\Administration\AdministrationController;
use App\Http\Controllers\ApiControllers\Users\UserController;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {

    Route::get('/getRoleList', [AdministrationController::class, 'getRoleList'])->middleware('can:' . RolePolicy::PERMISSION_GET_LIST);
    Route::get('/getRole', [AdministrationController::class, 'getRole'])->middleware('can:' . RolePolicy::PERMISSION_GET);
    Route::post('/addRole', [AdministrationController::class, 'addRole'])->middleware('can:' . RolePolicy::PERMISSION_ADD);
    Route::put('/updateRole', [AdministrationController::class, 'updateRole'])->middleware('can:' . RolePolicy::PERMISSION_UPDATE);
    Route::delete('/deleteRole', [AdministrationController::class, 'deleteRole'])->middleware('can:' . RolePolicy::PERMISSION_DELETE);

    Route::get('/getPermissionList', [AdministrationController::class, 'getPermissionList'])->middleware('can:' . PermissionPolicy::PERMISSION_GET_LIST);
    Route::post('/addPermission', [AdministrationController::class, 'addPermission'])->middleware('can:' . PermissionPolicy::PERMISSION_ADD);
    Route::put('/updatePermission', [AdministrationController::class, 'updatePermission'])->middleware('can:' . PermissionPolicy::PERMISSION_UPDATE);
    Route::get('/getPermission', [AdministrationController::class, 'getPermission'])->middleware('can:' . PermissionPolicy::PERMISSION_GET);
    Route::get('/deletePermission', [AdministrationController::class, 'deletePermission'])->middleware('can:' . PermissionPolicy::PERMISSION_DELETE);

    Route::post('/setUserRole', [AdministrationController::class, 'setUserRole'])->middleware('can:' . RolePolicy::PERMISSION_SET_ROLE);

	Route::post('/addUser', [UserController::class, 'addUser'])->middleware('can:' . UserPolicy::PERMISSION_ADD);
	Route::delete('/deleteUser', [UserController::class, 'deleteUser'])->middleware('can:' . UserPolicy::PERMISSION_DELETE);
	Route::put('/updateUser', [UserController::class, 'updateUser'])->middleware('can:' . UserPolicy::PERMISSION_UPDATE);

});


