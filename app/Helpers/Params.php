<?php

namespace App\Helpers;

class Params
{
    // === Contoh Konstanta Global ===
    public const STATUS_AKTIF = "AKTIF";
    public const STATUS_NONAKTIF = "NONAKTIF";

    public const ROLE_ADMIN = "administrator";
    public const ROLE_PELAKSANA = "pelaksana";
    public const ROLE_USER = "user";

    // ===  HARDCODE UNTUK TRANSACTIONAL ===
    public const BATAL_TICKET = "batal";
    public const DIPROSES_TICKET = "diproses";

    // === Contoh Nilai Statik Bisa diubah Dinamis ===
    public static string $defaultTimezone = "Asia/Jakarta";
    public static int $defaultPagination = 10;

    // === Contoh fungsi bantu (opsional) ===
    public static function getAllStatus(): array
    {
        return [self::STATUS_AKTIF, self::STATUS_NONAKTIF];
    }

    public static function getRoles(): array
    {
        return [self::ROLE_ADMIN, self::ROLE_PELAKSANA, self::ROLE_USER];
    }
}
