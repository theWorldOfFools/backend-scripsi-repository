<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function sendToUser($userId, $title, $message, array $metadata = [])
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'metadata' => $metadata,
            'is_read' => false
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
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->latest()
            ->get();
    }

    public function getAll($userId)
    {
        return Notification::where('user_id', $userId)->latest()->get();
    }
}


?>