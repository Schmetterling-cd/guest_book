<?php

use App\Http\Controllers\Administration\AdministrationController;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
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

});


