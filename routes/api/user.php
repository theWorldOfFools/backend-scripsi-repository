<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::prefix("users")->group(function () {
    Route::get("/userByDepartemen/{departemen_id}", [UserController::class, "getUserDataByDepartemen"]);
    
    Route::middleware(["role:admin"])->group(function () {
        Route::post("/", [UserController::class, "store"]);
        Route::put("/{id}", [UserController::class, "update"]);
        Route::delete("/{id}", [UserController::class, "destroy"]);
        Route::get("/{id}", [UserController::class, "show"]);
        Route::get("/", [UserController::class, "index"]);
        Route::get("/kpi", [UserController::class, "kpiTechnician"]);
    });
});
