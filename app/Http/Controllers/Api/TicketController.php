<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Params;
use App\Http\Controllers\Controller;
use App\Http\Requests\RedirectTicketTaskQuest;
use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTicketRequest;
use App\Models\User;
use App\Services\TelegramService;
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

    public function myTicketsTLDone($userId)
    {
        $results = $this->ticketService->getByUserIdTLDonePaginated($userId, 10, 1);
        return response()->json($results);
        // return response()->json($this->ticketService->getByUserId($userId));
    }

    public function Assignme($userId)
    {
        $results = $this->ticketService->getByUserAssigneIdPaginated($userId, 10, 1);
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

         if (!empty($data['assigned_to'])) {

            $userKepada = User::find($data['assigned_to']);
            $userDibuat = User::find($data['user_id']);

            if ($userKepada && $userKepada->telegram_chat_id) {

                TelegramService::send(
                    $userKepada->telegram_chat_id,
                    "Tiket Dibatalkan" 
                );

                TelegramService::send(
                    $userDibuat->telegram_chat_id,
                    "Tiket Dibatalkan" 
                );
            }
        }

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
            $userKepada = User::find($ticket->assigned_to);
        
        // Send To  Telegram 
           if ($userKepada && $userKepada->telegram_chat_id) {

                TelegramService::send(
                    $userKepada->telegram_chat_id,
                    "📌 Tiket Diprogress \n\n" .
                    "Teknisi yang mengerjakan : {$userKepada->name}"
                );
            }

        return response()->json(["message" => "Status Tiket Berubah menjadi Proses."]);
    }

     /**
     * @author tsany
     * Fungsi Untuk Dialihkan
     */
   public function dialihkanTicket(RedirectTicketTaskQuest $request)
    {
        $validated = $request->validated();

        $ticket = Ticket::findOrFail($validated['ticket_id']);

        $petugasLama = User::find($ticket->assigned_to);
        $ticket->dialihkan($validated['assigned_to'] ?? null);
        // Send To  Telegram 
            $userKepada = User::find($validated['assigned_to']);
           if ($userKepada && $userKepada->telegram_chat_id) {

                TelegramService::send(
                    $userKepada->telegram_chat_id,
                    "📌 Tiket Dialihkan \n\n" .
                     "Petugas Teknisi sebelumnya : {$petugasLama->name}\n" .
                    "dialikan ke Teknisi berikut yang mengerjakan : {$userKepada->name}"
                );
            }

        return response()->json([
            "message" => "Status tiket berhasil diubah menjadi Dialihkan."
        ],200);
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


    /**
     * Statistik dan Reporting Tiket Teknisi Dashboard
     *@author  Tsany
     */
    public function statistikTeknisi($userId)
    {
        $results = $this->ticketService->getTechnicianStatistics($userId);
        return response()->json($results);
    }
}
