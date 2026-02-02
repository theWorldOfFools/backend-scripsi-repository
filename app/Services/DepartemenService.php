<?php

namespace App\Services;

use App\Models\Category;
use App\Adapters\EloquentAdapter;
use App\Models\Departemen;
use PaginationLib\Pagination;

class DepartemenService
{

    /**
     * Get All data IT
     * @author gojoSatoru
     */
    public function getAllDataIT(
    ): array {
        $query = Departemen::query()->whereIn('id',[4,6]);

        // Gunakan adapter
        $adapter = new EloquentAdapter($query);

        // Buat Pagination instance
        $pagination = new Pagination($adapter, 10, 1, "");

        // Kembalikan hasil (data + meta)
        return $pagination->toArray();
    }
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
        $query = Departemen::query();

        // Gunakan adapter
        $adapter = new EloquentAdapter($query);

        // Buat Pagination instance
        $pagination = new Pagination($adapter, $perPage, $currentPage, "");

        // Kembalikan hasil (data + meta)
        return $pagination->toArray();
    }

    public function all()
    {
        return Departemen::all();
    }

    public function create(array $data)
    {
        return Departemen::create($data);
    }

    public function update(Departemen $departemen, array $data)
    {
        $departemen->update($data);
        return $departemen;
    }

    public function delete(Departemen $departemen)
    {
        return $departemen->delete();
    }
}
