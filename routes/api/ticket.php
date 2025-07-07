<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketController;

Route::prefix('tickets')->group(function () {
    Route::get('/', [TicketController::class, 'index']);
    Route::post('/', [TicketController::class, 'store']);
    Route::get('/{id}', [TicketController::class, 'show']);
    Route::put('/{id}', [TicketController::class, 'update']);
    Route::put('/{ticketId}/cancel', [TicketController::class, 'cancelTicket']);
    Route::get('/my-tickets/{userId}', [TicketController::class, 'myTickets']);
    Route::delete('/{id}', [TicketController::class, 'destroy']);
});
