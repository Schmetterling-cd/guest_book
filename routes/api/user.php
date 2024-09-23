<?php

use App\Http\Controllers\ApiControllers\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('users')->group(function () {
	Route::get('/me', [UserController::class, 'getMe']);
	Route::get('/getList', [UserController::class, 'getUserList']);
});
