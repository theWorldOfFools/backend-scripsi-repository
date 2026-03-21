<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        \Log::info('TELEGRAM MASUK:', $request->all());

        $chat_id = $request->input('message.chat.id');
        $text = $request->input('message.text');

        if (str_starts_with($text, '/start')) {

            $parts = explode(' ', $text);
            $token = $parts[1] ?? null;

            if ($token) {
                $user = User::where('telegram_token', $token)->first();

                if ($user) {
                    $user->update([
                        'telegram_chat_id' => $chat_id,
                        'telegram_token' => null // optional: biar token sekali pakai
                    ]);

                    // kirim balasan ke user
                    \App\Services\TelegramService::send(
                        $chat_id,
                        "Akun kamu berhasil terhubung ✅"
                    );
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function generateTelegramToken(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        \Log::info('USER:', [$user]);
        $token = Str::random(32);

        $user->update([
            'telegram_token' => $token
        ]);

        return response()->json([
            'url' => "https://t.me/HelpdeskResolveITBot?start=$token"
        ]);
    }
}



?>