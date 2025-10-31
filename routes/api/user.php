<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::prefix("users")->group(function () {
    Route::get("/", [UserController::class, "index"]);
    Route::post("/", [UserController::class, "store"]);
    Route::put("/{id}", [UserController::class, "update"]);
    Route::delete("/{id}", [UserController::class, "destroy"]);

    Route::middleware(["role:admin"])->group(function () {
        Route::get("/kpi", [UserController::class, "kpiTechnician"]);
    });
});
