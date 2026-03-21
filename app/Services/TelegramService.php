<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    public static function send($chatId, $message)
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        return Http::post(
            "https://api.telegram.org/bot{$token}/sendMessage",
            [
                'chat_id' => $chatId,
                'text' => $message
            ]
        );
    }
}