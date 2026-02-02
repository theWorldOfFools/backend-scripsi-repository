<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Izinkan preflight (OPTIONS) secara langsung
        if ($request->getMethod() === "OPTIONS") {
            return response("", 204)
                ->header("Access-Control-Allow-Origin", "*")
                ->header(
                    "Access-Control-Allow-Methods",
                    "GET, POST, PUT, PATCH, DELETE, OPTIONS",
                )
                ->header(
                    "Access-Control-Allow-Headers",
                    "Content-Type, Authorization, X-Requested-With",
                );
        }

        // Untuk request biasa
        $response = $next($request);

        return $response
            ->header("Access-Control-Allow-Origin", "*")
            ->header(
                "Access-Control-Allow-Methods",
                "GET, POST, PUT, PATCH, DELETE, OPTIONS",
            )
            ->header(
                "Access-Control-Allow-Headers",
                "Content-Type, Authorization, X-Requested-With",
            );
    }
}
