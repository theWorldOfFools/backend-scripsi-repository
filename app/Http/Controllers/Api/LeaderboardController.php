<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPoint;

class LeaderboardController extends Controller
{
    public function index()
    {
        $users = User::select(
                'id',
                'name',
                'rank',
                'total_points'
            )
            ->orderByDesc('total_points')
            ->limit(20)
            ->get();

        return response()->json([
            'data' => $users
        ]);
    }
    public function pointHistory($userId)
{
    $history = UserPoint::where(
            'user_id',
            $userId
        )
        ->latest()
        ->paginate(20);

    return response()->json([
        'data' => $history
    ]);
}
}