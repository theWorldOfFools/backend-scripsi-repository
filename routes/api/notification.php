<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationController;

Route::prefix('notifications')->group(function () {
    Route::get('/{userId}', [NotificationController::class, 'index']);
    Route::get('/unread/{userId}', [NotificationController::class, 'unread']);
    Route::get('/mark-read/{id}', [NotificationController::class, 'markAsRead']);
});
