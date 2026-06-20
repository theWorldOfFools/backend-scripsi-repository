<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserPoint;

class PointService
{
    public static function addPoint(
        $userId,
        $point,
        $action,
        $ticketId = null,
        $description = null
    )
    {
        UserPoint::create([
            'user_id' => $userId,
            'ticket_id' => $ticketId,
            'points' => $point,
            'action' => $action,
            'description' => $description
        ]);

        $user = User::find($userId);

        if (!$user) {
            return;
        }

        $user->total_points += $point;

        $user->rank = self::calculateRank(
            $user->total_points
        );

        $user->save();
    }

    public static function calculateRank($point)
    {
        if ($point >= 1500) {
            return 'Diamond';
        }

        if ($point >= 700) {
            return 'Platinum';
        }

        if ($point >= 300) {
            return 'Gold';
        }

        if ($point >= 100) {
            return 'Silver';
        }

        return 'Bronze';
    }
}