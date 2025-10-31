<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketController;

Route::prefix("tickets")->group(function () {
    Route::post("/", [TicketController::class, "store"]);
    Route::middleware(["role:admin"])->group(function () {
        Route::get("/", [TicketController::class, "index"]);
        Route::get("/statistics", [TicketController::class, "statistics"]);
        Route::delete("/{id}", [TicketController::class, "destroy"]);
    });

    Route::put("/{id}", [TicketController::class, "update"]);
    Route::put("/{ticketId}/cancel", [TicketController::class, "cancelTicket"]);
    Route::get("/my-tickets/{userId}", [TicketController::class, "myTickets"]);
    //fixing bug route
    Route::get("/{id}", [TicketController::class, "show"]);
});
