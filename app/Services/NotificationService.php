<?php

namespace App\Services;

use App\Models\Notification;
use App\Adapters\EloquentAdapter;
use PaginationLib\Pagination;

class NotificationService
{
    public function sendToUser($userId, $title, $message, array $metadata = [])
    {
        return Notification::create([
            "user_id" => $userId,
            "title" => $title,
            "message" => $message,
            "metadata" => $metadata,
            "is_read" => false,
        ]);
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::findOrFail($notificationId);
        $notification->is_read = true;
        $notification->save();
        return $notification;
    }

    public function getUnread($userId)
    {
        return Notification::where("user_id", $userId)
            ->where("is_read", false)
            ->latest()
            ->get();
    }

    public function getAll($userId)
    {
        return Notification::where("user_id", $userId)->latest()->get();
    }

    /**
     * Get All data with pagination
     * @param perPage (limit)
     * @param currentPage  ( offset)
     * @author gojoSatoru
     */
    public function getAllPaginated(
        int $userId,
        int $perPage = 10,
        int $currentPage = 1,
    ): array {
        $query = Notification::where("user_id", $userId)->latest();

        // Gunakan adapter
        $adapter = new EloquentAdapter($query);

        // Buat Pagination instance
        $pagination = new Pagination($adapter, $perPage, $currentPage, "");

        // Kembalikan hasil (data + meta)
        return $pagination->toArray();
    }
}
