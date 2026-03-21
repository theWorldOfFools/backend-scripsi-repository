<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\TelegramController;

Route::prefix('notifications')->group(function () {

    // 🔒 HARUS LOGIN (JWT)
    Route::middleware(['jwt.auth'])->group(function () {
        Route::get('/{userId}', [NotificationController::class, 'index']);
        Route::get('/unread/{userId}', [NotificationController::class, 'unread']);
        Route::get('/mark-read/{id}', [NotificationController::class, 'markAsRead']);

        Route::get('/telegram/connect', [TelegramController::class, 'generateTelegramToken']);
    });

    // 🌐 PUBLIC (Telegram webhook)
    Route::post('/telegram/webhook', [TelegramController::class, 'webhook']);
});
