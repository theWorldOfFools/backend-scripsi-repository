<?php

namespace App\Services;

use App\Adapters\EloquentAdapter;
use App\Models\Ticket;
use App\Models\User;
use PaginationLib\Pagination;
use Illuminate\Support\Facades\DB;

class UserService
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
        $query = User::query();

        // Gunakan adapter
        $adapter = new EloquentAdapter($query);

        // Buat Pagination instance
        $pagination = new Pagination($adapter, $perPage, $currentPage, "");

        // Kembalikan hasil (data + meta)
        return $pagination->toArray();
    }

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
        return User::where("user_id", $userId)->latest()->get();
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

    public function getTechnicianKpi()
    {
        return Ticket::selectRaw(
            "
                COALESCE(u.name, 'Unassigned') as technician,
                COUNT(tickets.id) as total_ticket,
                SUM(CASE WHEN tickets.status = 'selesai' THEN 1 ELSE 0 END) as completed_ticket
            ",
        )
            ->leftJoin("users as u", "tickets.assigned_to", "=", "u.id")
            ->groupBy("u.name")
            ->get();
    }
}
