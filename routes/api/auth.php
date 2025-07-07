<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/updateProfile/{id}', [AuthController::class, 'updateProfile']);
    Route::get('/me/{id}', [AuthController::class, 'profile']);
    Route::put('/updatePassword/{id}', [AuthController::class, 'updatePassword']);

});
