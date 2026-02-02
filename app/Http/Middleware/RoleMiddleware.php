<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    /**
     * Handle an incoming request with JWT and role check.
     */
    public function handle($request, Closure $next, ...$roles)
    {
        try {
            // Autentikasi user dari token JWT
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            return response()->json(
                ["message" => "Unauthorized or invalid token"],
                401,
            );
        }

        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }

        // Validasi role user
        if (!in_array($user->role, $roles)) {
            return response()->json(
                ["message" => "Forbidden: insufficient role"],
                403,
            );
        }

        // Simpan user agar bisa diakses controller
        $request->merge(["auth_user" => $user]);

        return $next($request);
    }
}
