<?php

use App\Http\Controllers\ApiControllers\GuestBookController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/api/admin.php';
require __DIR__.'/api/user.php';

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/getResponseToComment', [GuestBookController::class, 'getResponseToComment'])->middleware('can:getResponseToComment');
    Route::post('/addResponseToComment', [GuestBookController::class, 'addResponseToComment'])->middleware('can:addResponseToComment');
    Route::put('/updateResponseToComment', [GuestBookController::class, 'updateResponseToComment'])->middleware('can:updateResponseToComment');
    Route::delete('/deleteResponseToComment', [GuestBookController::class, 'deleteResponseToComment'])->middleware('can:deleteResponseToComment');
    Route::get('/getCommentResponseList/{page}', [GuestBookController::class, 'getCommentResponseList'])->middleware('can:getCommentResponseList');

    Route::post('/addComment', [GuestBookController::class, 'addComment'])->middleware('can:addComment');
    Route::put('/updateComment', [GuestBookController::class, 'updateComment'])->middleware('can:updateComment');
    Route::delete('/deleteComment', [GuestBookController::class, 'deleteComment'])->middleware('can:deleteComment');
    Route::get('/getComment', [GuestBookController::class, 'getComment'])->middleware('can:getComment');
    Route::get('/getCommentList/{page}', [GuestBookController::class, 'getCommentList'])->middleware('can:getCommentList');

});
