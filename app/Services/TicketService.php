<?php 
namespace App\Services;

use App\Models\Notification;
use App\Models\Ticket;
use App\Models\TicketComment;

class TicketService
{
    public function getAll()
    {
        return Ticket::with(['user', 'category', 'assignedUser'])->latest()->get();
    }

    public function getById($id)
    {
        return Ticket::with(['comments.user', 'attachments'])->findOrFail($id);
    }

public function create(array $data)
{
    $ticket = Ticket::create($data);

    // Simpan komentar awal saat tiket dibuat
    TicketComment::create([
        'ticket_id' => $ticket->id,
        'user_id' => $data['user_id'],
        'message' => 'Tiket dibuat dengan status: ' . ($ticket->status ?? 'baru'),
    ]);


    // Simpan komentar awal saat tiket dibuat
    Notification::create([
        'user_id' => $data['user_id'],
        'title'=>'Berhasil Buat Ticket ',
        'message' => 'Tiket dibuat dengan status: ' . ($ticket->status ?? 'baru'),
        'is_read'=>false
    ]);

    return $ticket;
}


   public function getByUserId($userId)
    {
        return Ticket::where('user_id', $userId)
            ->with(['comments.user', 'attachments', 'category'])
            ->latest()
            ->get();
    }
 public function update($id, array $data)
{
    $ticket = Ticket::findOrFail($id);
    $ticket->update($data);

    // Simpan komentar update status atau deskripsi
    $userId = $data['user_id'] ?? $ticket->user_id ?? null;

    if ($userId) {
        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $userId,
            'message' => 'Tiket diperbarui. Status saat ini: ' . ($ticket->status ?? '-'),
        ]);
    }

    return $ticket;
}

    public function delete($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
    }
}


?>