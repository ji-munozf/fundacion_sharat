<?php

use App\Http\Controllers\Portal\InstitutionController;
use App\Http\Controllers\Portal\PermissionController;
use App\Http\Controllers\Portal\RoleController;
use App\Http\Controllers\Portal\UserController;
use App\Http\Controllers\Portal\VacancyController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('/users', UserController::class)
    ->names('portal.users')
    ->except('show');
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
    Route::get('/vacancies/vacancies_posted', [VacancyController::class, 'vacanciesPosted'])
    ->name('portal.vacancies.vacancies_posted');
    Route::get('/vacancies/vacancies_posted/request_vacancy/{vacancy}', [VacancyController::class, 'requestVacancy'])
    ->name('portal.vacancies.requestVacancy');
    Route::get('/vacancies/{vacancy}/candidates', [VacancyController::class, 'candidates'])
    ->name('portal.vacancies.candidates');
    Route::post('/vacancies/{vacancy}/candidates', [VacancyController::class, 'sendRequestVacancy'])
    ->name('portal.vacancies.sendRequestVacancy');
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