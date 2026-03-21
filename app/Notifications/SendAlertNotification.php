<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Services\TelegramService;
use App\Services\WhatsAppService;

class SendAlertNotification extends Notification
{
    use Queueable;

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['custom'];
    }

    public function toCustom($notifiable)
    {
        // Kirim Telegram
        if ($notifiable->telegram_chat_id) {
            TelegramService::send(
                $notifiable->telegram_chat_id,
                $this->message
            );
        }

        // Kirim WhatsApp
        if ($notifiable->no_telepon) {
            WhatsAppService::send(
                $notifiable->no_telepon,
                $this->message
            );
        }

        return true;
    }
}