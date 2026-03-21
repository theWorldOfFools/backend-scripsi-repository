<?php

namespace App\Adapters;

use Illuminate\Database\Eloquent\Builder;
use PaginationLib\Adapters\AdapterInterface;

class EloquentAdapter implements AdapterInterface
{
    protected Builder $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * Ambil item berdasarkan offset & limit (untuk pagination)
     */
    public function getItems(int $offset, int $limit): array
    {
        return $this->query->offset($offset)->limit($limit)->get()->toArray();
    }

    /**
     * Hitung total semua item (tanpa limit)
     */
    public function count(): int
    {
        return $this->query->count();
    }

    /**
     * Alias atau implementasi tambahan untuk total item (sesuai interface)
     */
    public function getTotalItems(): int
    {
        return $this->count();
    }

    /**
     * Ambil potongan data (slice) berdasarkan offset & panjang
     */
    public function getSlice(int $offset, int $length): array
    {
        return $this->query->offset($offset)->limit($length)->get()->toArray();
    }
}
