<?php 
namespace App\Http\Controllers\Api;

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
        return response()->json($this->ticketService->getAll());
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
    return response()->json($this->ticketService->getByUserId($userId));
}


    public function cancelTicket($ticketId)
{
    $ticket = Ticket::findOrFail($ticketId);
    $ticket->status = 'batal';
    $ticket->save();

    // Buat komentar status "batal"
    TicketComment::create([
        'ticket_id' => $ticketId,
        'user_id' => $ticket->user_id,
        'message' => 'Tiket dibatalkan.',
    ]);

    return response()->json(['message' => 'Tiket berhasil dibatalkan.']);
}

    public function update(Request $request, $id)
    {
        return response()->json($this->ticketService->update($id, $request->all()));
    }

    public function destroy($id)
    {
        $this->ticketService->delete($id);
        return response()->json(['message' => 'Deleted'], 204);
    }
}


?>