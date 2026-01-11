<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api/auth.php'));

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api/ticket.php'));

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api/comment.php'));

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api/attachment.php'));

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api/notification.php'));
        });
    }
}
