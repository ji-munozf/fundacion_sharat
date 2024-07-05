<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'can:Acceso al dashboard',
])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('portal.dashboard');
});

require __DIR__ . '/portal.php';
