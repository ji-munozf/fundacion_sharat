<?php

use App\Http\Controllers\PortalRecruitmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/portal-recruitment', [PortalRecruitmentController::class, 'index'])
    ->name('portal_recruitment');

Route::post('/portal-recruitment', [PortalRecruitmentController::class, 'store'])
    ->name('portal_recruitment_store');

require __DIR__ . '/portal.php';
