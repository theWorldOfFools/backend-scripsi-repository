<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttachmentController;

Route::prefix('attachments')->group(function () {
    Route::post('/', [AttachmentController::class, 'store']);
    Route::delete('/{id}', [AttachmentController::class, 'destroy']);
});
