<?php

namespace App\Services;

use App\Adapters\EloquentAdapter;
use App\Models\TicketComment;
use PaginationLib\Pagination;
class CommentService
{
    public function getAllPaginated(
        int $perPage = 10,
        int $currentPage = 1,
    ): array {
        $query = TicketComment::with(["ticket", "user"])->latest();

        // Gunakan adapter
        $adapter = new EloquentAdapter($query);

        // Buat Pagination instance
        $pagination = new Pagination($adapter, $perPage, $currentPage, "");

        // Kembalikan hasil (data + meta)
        return $pagination->toArray();
    }

    public function getByTicketIdPaginated(
        int $ticketId,
        int $perPage = 10,
        int $currentPage = 1,
    ): array {
        $query = TicketComment::where("ticket_id", $ticketId)
            ->with("user")
            ->orderBy("created_at", "asc");

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

    public function getAll()
    {
        return TicketComment::with(["ticket", "user"])
            ->latest()
            ->get();
    }

    public function create(array $data)
    {
        return TicketComment::create($data);
    }

    public function update($id, array $data)
    {
        $comment = TicketComment::findOrFail($id);
        $comment->update($data);
        return $comment;
    }

    public function getByTicketId($ticketId)
    {
        return TicketComment::where("ticket_id", $ticketId)
            ->with("user")
            ->orderBy("created_at", "asc")
            ->get();
    }

    public function delete($id)
    {
        $comment = TicketComment::findOrFail($id);
        $comment->delete();
    }
}
