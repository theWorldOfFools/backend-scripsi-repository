<?php

namespace App\Services;

use App\Adapters\EloquentAdapter;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserDivisi;
use PaginationLib\Pagination;
use Illuminate\Support\Facades\Hash;
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
       $query = User::with('divisi.departemen');

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
        return User::with(['divisi'])->findOrFail($id);
    }

    public function getDataByDepartemen($departemen_id)
    {
        return UserDivisi::with([
            'user' => function($query) {
                $query->select('id','no_telepon', 'name', 'role');  // kolom yang kamu butuhkan dari user
            },
            'departemen' => function($query) {
                $query->select('id','name'); // kolom yang kamu butuhkan dari departemen
            }
        ])->where('departemen_id',$departemen_id)->get();
    }


    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $departemenIds = $data['departemen_id'];

            unset($data['departemen_id']);

            $user = User::create($data);

            foreach ($departemenIds as $depId) {

                UserDivisi::create([
                    'user_id' => $user->id,
                    'departemen_id' => $depId
                ]);

            }

            return $user;
        });
    }

    public function getDetailUser($userId)
    {
        return User::where("user_id", $userId)->latest()->get();
    }
    
    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $user = User::findOrFail($id);

            if(isset($data['password'])){
                $data['password'] = Hash::make($data['password']);
            }

            $departemenIds = $data['departemen_id'];

            unset($data['departemen_id']);

            $user->update($data);

            // hapus relasi lama
            UserDivisi::where('user_id',$user->id)->delete();

            foreach ($departemenIds as $depId) {

                UserDivisi::create([
                    'user_id' => $user->id,
                    'departemen_id' => $depId
                ]);

            }

            return $user;
        });
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
