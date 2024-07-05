<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Portal\InstitutionController;
use App\Http\Controllers\Portal\PermissionController;
use App\Http\Controllers\Portal\PlanController;
use App\Http\Controllers\Portal\PostulationController;
use App\Http\Controllers\Portal\RoleController;
use App\Http\Controllers\Portal\UserController;
use App\Http\Controllers\Portal\VacancyController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'can:Acceso al dashboard',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('portal.dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('/users', UserController::class)
        ->names('portal.users')
        ->except('show');
    Route::get('/users/admins', [UserController::class, 'indexAdmins'])
        ->name('portal.users.role.admin');
    Route::get('/users/institutions', [UserController::class, 'indexInstitutions'])
        ->name('portal.users.role.institution');
    Route::get('/users/applicants', [UserController::class, 'indexPostulations'])
        ->name('portal.users.role.postulation');
    Route::get('/users/{user}/password', [UserController::class, 'password'])
        ->name('portal.users.password');
    Route::post('/users/{user}/password', [UserController::class, 'updatePass'])
        ->name('portal.users.updatePass');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('/roles', RoleController::class)
        ->names('portal.roles')
        ->except('show');
    Route::get('/roles/{id}/give-permissions', [RoleController::class, 'addPermissionToRole'])
        ->name('portal.roles.addPermissionToRole');
    Route::put('/roles/{id}/give-permissions', [RoleController::class, 'givePermissionToRole'])
        ->name('portal.roles.givePermissionToRole');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('/permissions', PermissionController::class)
        ->names('portal.permissions')
        ->except('show');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('/vacancies', VacancyController::class)
        ->names('portal.vacancies')
        ->except('show');
    Route::get('/vacancies/{vacancy}/candidates', [VacancyController::class, 'candidates'])
        ->name('portal.vacancies.candidates');
    Route::get('vacancies/download-cv/{id}', [VacancyController::class, 'downloadCV'])
        ->name('portal.vacancies.downloadCV');

    Route::get('/postulation/{postulation}/accept', [VacancyController::class, 'showAcceptForm'])->name('postulation.showAcceptForm');
    Route::post('/postulation/{postulation}/accept', [VacancyController::class, 'acceptPostulation'])->name('postulation.accept');

    Route::get('/postulation/{postulation}/reject', [VacancyController::class, 'showRejectForm'])->name('postulation.showRejectForm');
    Route::post('/postulation/{postulation}/reject', [VacancyController::class, 'rejectPostulation'])->name('postulation.reject');

    Route::get('/postulation/{postulation}/edit-reasons', [VacancyController::class, 'editReasonsForm'])->name('postulation.editReasonsForm');
    Route::post('/postulation/{postulation}/edit-reasons', [VacancyController::class, 'updateReasons'])->name('postulation.updateReasons');
    Route::get('/postulation/cancel/{id}', [VacancyController::class, 'cancelPostulation'])->name('postulation.cancel');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('/institutions', InstitutionController::class)
        ->names('portal.institutions')
        ->except('show');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('/postulations', PostulationController::class)
        ->names('portal.postulations')
        ->except(['create', 'store', 'show']);
    Route::get('/postulations/request_vacancy/{vacancy}', [PostulationController::class, 'requestVacancy'])
        ->name('portal.postulations.request_vacancy');
    Route::post('/postulations/request_vacancy/{vacancy}', [PostulationController::class, 'sendRequestVacancy'])
        ->name('portal.postulations.sendRequestVacancy');
    Route::get('/postulations/{id}/reasons', [PostulationController::class, 'showReasons'])->name('portal.postulations.showReasons');

});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('/plans', PlanController::class)
        ->names('portal.plans')
        ->except('show');
});
