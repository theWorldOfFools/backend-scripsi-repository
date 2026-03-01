<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketController;

Route::prefix("tickets")->group(function () {
    Route::post("/", [TicketController::class, "store"]);
    Route::get("/", [TicketController::class, "index"]);
    Route::put("/updateStatusProgress/{ticketId}", [TicketController::class, "progressTicket"]);
    Route::middleware(["role:admin"])->group(function () {
        Route::get("/statistics", [TicketController::class, "statistics"]);
        Route::delete("/{id}", [TicketController::class, "destroy"]);
    });


    /**
     * @author tsany
     * Segmen Role Teknisi
     */
    Route::get("/statistik_teknisi/{userId}", [TicketController::class, "statistikTeknisi"]);
    Route::middleware(['role:teknisi'])->group(function(){
    Route::post("/dialihkanTiket", [TicketController::class, "dialihkanTicket"]);
    });
    Route::put("/{id}", [TicketController::class, "update"]);
    Route::put("/{ticketId}/cancel", [TicketController::class, "cancelTicket"]);
    Route::get("/my-tickets/{userId}", [TicketController::class, "myTickets"]);
    Route::get("/my-tickets-tl-done/{userId}", [TicketController::class, "myTicketsTLDone"]);
    Route::get("/track-assigne-me/{userId}", [TicketController::class, "Assignme"]);
    //fixing bug route
    Route::get("/{id}", [TicketController::class, "show"]);
});
