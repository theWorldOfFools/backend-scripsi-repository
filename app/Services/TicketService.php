<?php
namespace App\Services;

use App\Models\Notification;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Adapters\EloquentAdapter;
use PaginationLib\Pagination;
use Illuminate\Support\Facades\DB;

class TicketService
{
    /**
     * Get All data with pagination
     * @param perPage (limit)
     * @param currentPage  ( offset)
     * @author gojoSatoru
     */
    public function getAllPaginated(
        int $perPage = 10,
        int $currentPage = 1,
    ): array {
        $query = Ticket::with(["user", "category", "assignedUser"])->latest();

        // Gunakan adapter
        $adapter = new EloquentAdapter($query);

        // Buat Pagination instance
        $pagination = new Pagination($adapter, $perPage, $currentPage, "");

        // Kembalikan hasil (data + meta)
        return $pagination->toArray();
    }

    /**
     * Display get All data
     */
    public function getAll()
    {
        return Ticket::with(["user", "category", "assignedUser"])
            ->latest()
            ->get();
    }

    /**
     * @param ${id} int idTicket
     * @author gojoSatoru
     */
    public function getById($id)
    {
        return Ticket::with(["comments.user", "attachments"])->findOrFail($id);
    }

    public function create(array $data)
    {
        $ticket = Ticket::create($data);

        // Simpan komentar awal saat tiket dibuat
        TicketComment::create([
            "ticket_id" => $ticket->id,
            "user_id" => $data["user_id"],
            "message" =>
                "Tiket dibuat dengan status: " . ($ticket->status ?? "baru"),
        ]);

        // Simpan komentar awal saat tiket dibuat
        Notification::create([
            "user_id" => $data["user_id"],
            "title" => "Berhasil Buat Ticket ",
            "message" =>
                "Tiket dibuat dengan status: " . ($ticket->status ?? "baru"),
            "is_read" => false,
        ]);

        return $ticket;
    }

    /**
     * @param {userId} int (login_id)
     * @param {perPage} int (limit )
     * @param {currentPage} int (offset )
     * @author gojoSatoru
     */
    public function getByUserAssigneIdPaginated(
        int $userId,
        int $perPage = 10,
        int $currentPage = 1,
    ): array {
        $query = Ticket::where("assigned_to", $userId)
            ->with(["comments.user", "attachments", "assignedUser", "category"])
            ->latest();

        $adapter = new EloquentAdapter($query);

        $pagination = new Pagination(
            $adapter,
            $perPage,
            $currentPage,
            // route("tickets.comments", ["ticket" => $ticketId]), -- no routing because this is for json response
            "",
        );

        return $pagination->toArray();
    }











    /**
     * @param {userId} int (login_id)
     * @param {perPage} int (limit )
     * @param {currentPage} int (offset )
     * @author gojoSatoru
     */
    public function getByUserIdPaginated(
        int $userId,
        int $perPage = 10,
        int $currentPage = 1,
    ): array {
        $query = Ticket::where("user_id", $userId)
            ->with(["comments.user", "attachments", "assignedUser", "category"])
            ->latest();

        $adapter = new EloquentAdapter($query);

        $pagination = new Pagination(
            $adapter,
            $perPage,
            $currentPage,
            // route("tickets.comments", ["ticket" => $ticketId]), -- no routing because this is for json response
            "",
        );

        return $pagination->toArray();
    }

    public function update($id, array $data)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($data);

        // Simpan komentar update status atau deskripsi
        $userId = $data["user_id"] ?? ($ticket->user_id ?? null);

        if ($userId) {
            TicketComment::create([
                "ticket_id" => $ticket->id,
                "user_id" => $userId,
                "message" =>
                    "Tiket diperbarui. Status saat ini: " .
                    ($ticket->status ?? "-"),
            ]);
        }

        return $ticket;
    }

    public function delete($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
    }

    public function getStatistics()
    {
        try {
            $totalTickets = Ticket::count();

            $byStatus = Ticket::selectRaw("status, COUNT(*) as total")
                ->groupBy("status")
                ->pluck("total", "status");

            $byCategory = Ticket::selectRaw(
                "c.name as category, COUNT(tickets.id) as total",
            )
                ->leftJoin(
                    "categories as c",
                    "tickets.category_id",
                    "=",
                    "c.id",
                )
                ->groupBy("c.name")
                ->pluck("total", "category");

            $byUrgensi = Ticket::selectRaw("urgensi, COUNT(*) as total")
                ->groupBy("urgensi")
                ->pluck("total", "urgensi");

            return [
                "total_tickets" => $totalTickets,
                "status_summary" => $byStatus,
                "category_summary" => $byCategory,
                "urgency_summary" => $byUrgensi,
            ];
        } catch (\Throwable $e) {
            return response()->json(
                [
                    "error" => $e->getMessage(),
                    "trace" => $e->getTraceAsString(),
                ],
                500,
            );
        }
    }
}

?>
