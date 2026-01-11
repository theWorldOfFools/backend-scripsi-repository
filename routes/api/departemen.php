<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DepartemenController;

Route::prefix("departemen")->group(function () {

    Route::middleware(["role:admin"])->group(function () {
        Route::get("/", [DepartemenController::class, "index"]);
        Route::post("/", [DepartemenController::class, "store"]);
        Route::put("/{departemen}", [DepartemenController::class, "update"]);
        Route::delete("/{departemen}", [DepartemenController::class, "destroy"]);
    });

});

