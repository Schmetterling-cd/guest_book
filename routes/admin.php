<?php

use App\Http\Controllers\Administration\AdministrationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {

    Route::get('/getRoleList', [AdministrationController::class, 'getRoleList'])->middleware('can:getRoleList');
    Route::get('/getRole', [AdministrationController::class, 'getRole'])->middleware('can:getRole');
    Route::post('/addRole', [AdministrationController::class, 'addRole'])->middleware('can:addRole');
    Route::put('/updateRole', [AdministrationController::class, 'updateRole'])->middleware('can:updateRole');
    Route::delete('/deleteRole', [AdministrationController::class, 'deleteRole'])->middleware('can:deleteRole');

    Route::get('/getPermissionList', [AdministrationController::class, 'getPermissionList'])->middleware('can:getPermissionList');
    Route::post('/addPermission', [AdministrationController::class, 'addPermission'])->middleware('can:addPermission');
    Route::put('/updatePermission', [AdministrationController::class, 'updatePermission'])->middleware('can:updatePermission');
    Route::get('/getPermission', [AdministrationController::class, 'getPermission'])->middleware('can:getPermission');

    Route::post('/setUserRole', [AdministrationController::class, 'setUserRole'])->middleware('can:setUserRole');

});


