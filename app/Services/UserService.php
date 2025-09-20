<?php 
namespace App\Services;

use App\Models\Notification;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;

class UserService
{
    public function getAll()
    {
        return User::latest()->get();
    }

    public function getById($id)
    {
        return User::findOrFail($id);
    }

public function create(array $data)
{
    $ticket = User::create($data);

    return $ticket;
}


   public function getDetailUser($userId)
    {
        return User::where('user_id', $userId)
            ->latest()
            ->get();
    }
 public function update($id, array $data)
{
    $user = User::findOrFail($id);
    $user->update($data);

    return $user;
}

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
}


?>