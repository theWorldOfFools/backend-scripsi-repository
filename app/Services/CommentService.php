<?php 

namespace App\Services;

use App\Models\TicketComment;

class CommentService
{

     public function getAll()
    {
        return TicketComment::with(['ticket', 'user'])->latest()->get();
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
        return TicketComment::where('ticket_id', $ticketId)
            ->with('user') // jika kamu ingin juga tampilkan user yang mengomentari
            ->orderBy('created_at', 'asc')
            ->get();
    }


    public function delete($id)
    {
        $comment = TicketComment::findOrFail($id);
        $comment->delete();
    }
}

?>