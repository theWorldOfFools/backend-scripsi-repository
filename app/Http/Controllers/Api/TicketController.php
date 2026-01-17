<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Params;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTicketRequest;
use App\Services\TicketService;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index()
    {
        // return response()->json($this->ticketService->getAll());
        $results = $this->ticketService->getAllPaginated(10, 1);
        return response()->json($results);
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = $this->ticketService->create($request->validated());
        return response()->json($ticket, 201);
    }

    public function show($id)
    {
        return response()->json($this->ticketService->getById($id));
    }

    public function myTickets($userId)
    {
        $results = $this->ticketService->getByUserIdPaginated($userId, 10, 1);
        return response()->json($results);
        // return response()->json($this->ticketService->getByUserId($userId));
    }

    public function cancelTicket($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->status = Params::BATAL_TICKET;
        $ticket->save();

        // Buat komentar status "batal"
        TicketComment::create([
            "ticket_id" => $ticketId,
            "user_id" => $ticket->user_id,
            "message" => "Tiket dibatalkan.",
        ]);

        return response()->json(["message" => "Tiket berhasil dibatalkan."]);
    }


    /**
     * @author tsany
     * Fungsi Untuk progress
     */
        public function progressTicket($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->status = Params::DIPROSES_TICKET;
        $ticket->save();

        // Buat komentar status "batal"
        TicketComment::create([
            "ticket_id" => $ticketId,
            "user_id" => $ticket->user_id,
            "message" => "Tiket Berubah menjadi Proses.",
        ]);

        return response()->json(["message" => "Status Tiket Berubah menjadi Proses."]);
    }

    public function update(Request $request, $id)
    {
        return response()->json(
            $this->ticketService->update($id, $request->all()),
        );
    }

    public function destroy($id)
    {
        $this->ticketService->delete($id);
        return response()->json(["message" => "Deleted"], 204);
    }

    /**
     * Statistik dan Reporting Tiket
     * @author tsany <ryzen12021@gmail.com>
     */
    public function statistics()
    {
        $stats = $this->ticketService->getStatistics();
        // dd($stats);
        return response()->json($stats);
    }
}
