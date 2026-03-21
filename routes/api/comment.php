<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommentController;

Route::prefix('comments')->group(function () {
    Route::get('/', [CommentController::class, 'index']);
    Route::get('/getTicket/{ticketId}', [CommentController::class, 'getStatus']);
    Route::get('/{id}', [CommentController::class, 'show']);
    Route::get('/{id}', [CommentController::class, 'show']);
    Route::put('/{id}', [CommentController::class, 'update']);
    Route::delete('/{id}', [CommentController::class, 'destroy']);
});
