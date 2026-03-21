
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'API OK']);
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
require __DIR__ . '/api/auth.php';
require __DIR__ . '/api/ticket.php';
require __DIR__ . '/api/comment.php';
require __DIR__ . '/api/attachment.php';
require __DIR__ . '/api/notification.php';
require __DIR__ . '/api/category.php';
require __DIR__ . '/api/departemen.php';

require __DIR__ . '/api/user.php';

