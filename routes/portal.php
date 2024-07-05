<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Portal\InstitutionController;
use App\Http\Controllers\Portal\PermissionController;
use App\Http\Controllers\Portal\PlanController;
use App\Http\Controllers\Portal\PostulationController;
use App\Http\Controllers\Portal\PremiumBenefitController;
use App\Http\Controllers\Portal\ReportController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('portal.dashboard');
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
    Route::get('/users/{user}/make-premium', [UserController::class, 'makePremiumView'])
        ->name('portal.users.makePremiumView');
    Route::post('/users/{user}/make-premium/', [UserController::class, 'makePremium'])
        ->name('portal.users.makePremium');
    Route::delete('/users/{user}/cancel-subscription', [UserController::class, 'cancelSubscription'])
        ->name('portal.users.cancelSubscription');
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
    Route::get('/postulation/check-eliminated/{id}', [VacancyController::class, 'checkPostulationEliminated'])->name('postulation.checkEliminated');

    Route::get('/postulation/destroy/{id}', [VacancyController::class, 'destroyPostulante'])->name('postulation.destroy');

    Route::post('/vacancies/{vacancy}/revert-destroy', [VacancyController::class, 'revertirDestroyPostulante'])
        ->name('postulation.revertDestroy');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('/institutions', InstitutionController::class)
        ->names('portal.institutions')
        ->except('show');
    Route::get('/institutions/change/{id}', [InstitutionController::class, 'changeIsActive'])
        ->name('portal.institutions.change');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('/postulations', PostulationController::class)
        ->names('portal.postulations')
        ->except(['create', 'store', 'show']);
    Route::get('/postulations/request-vacancy/{vacancy}', [PostulationController::class, 'requestVacancy'])
        ->name('portal.postulations.request_vacancy')
        ->middleware('check.postulation.limit');
    Route::post('/postulations/request-vacancy/{vacancy}', [PostulationController::class, 'sendRequestVacancy'])
        ->name('portal.postulations.sendRequestVacancy');
    Route::get('/postulations/{id}/reasons', [PostulationController::class, 'showReasons'])
        ->name('portal.postulations.showReasons');
    Route::get('/postulations/userData', [PostulationController::class, 'getUserData'])
        ->name('portal.postulations.userData');
    Route::post('/postulations/save-selected', [PostulationController::class, 'saveSelected'])
        ->name('postulations.saveSelected');
    Route::post('/postulations/{postulation}/cancel', [PostulationController::class, 'cancelPostulation'])
        ->name('portal.postulations.cancel');
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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/postulation_data', [PremiumBenefitController::class, 'postulationData'])
        ->name('portal.premium_benefits.postulation_data');
    Route::post('/postulation_data', [PremiumBenefitController::class, 'savePostulationData'])
        ->name('portal.premium_benefits.savePostulationData');
    Route::get('/postulation_data/{postulationUserData}/edit_postulation_data', [PremiumBenefitController::class, 'editPostulationData'])
        ->name('portal.premium_benefits.edit_postulation_data');
    Route::put('/postulation_data/{postulationUserData}/edit_postulation_data', [PremiumBenefitController::class, 'updatePostulationData'])
        ->name('portal.premium_benefits.updatePostulationData');
    Route::delete('/postulation_data', [PremiumBenefitController::class, 'destroyPostulationData'])
        ->name('portal.premium_benefits.destroyPostulationData');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('/reports', ReportController::class)
        ->names('portal.reports')
        ->except(['create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::get('reports/vacancies', [ReportController::class, 'vacancyIndex'])
        ->name('portal.reports.vacancies');
    Route::get('reports/vacancies/export', [ReportController::class, 'exportToExcelVacancies'])
        ->name('vacancies.export');
    Route::post('/revertir-eliminar-vacancy/{id}', [ReportController::class, 'revertirEliminarVacancy'])
        ->name('revertir-eliminar-vacancy');
    Route::delete('reports/vacancies/clean', [ReportController::class, 'cleanVacancies'])
        ->name('clean.vacancies');
    Route::get('reports/postulations', [ReportController::class, 'postulationIndex'])
        ->name('portal.reports.postulations');
    Route::get('reports/postulations/export', [ReportController::class, 'exportToExcelPostulations'])
        ->name('postulations.export');
    Route::post('/revertir-eliminar-postulation/{id}', [ReportController::class, 'revertirEliminarPostulation'])
        ->name('revertir-eliminar-postulation');
    Route::delete('reports/postulations/clean', [ReportController::class, 'cleanPostulations'])
        ->name('clean.postulations');
    Route::get('reports/subscriptions', [ReportController::class, 'subscriptionIndex'])
        ->name('portal.reports.subscriptions');
    Route::get('reports/subscriptions/export', [ReportController::class, 'exportToExcelSubs'])
        ->name('subscriptions.export');
    Route::delete('reports/subscriptions/clean', [ReportController::class, 'cleanSubscriptions'])
        ->name('clean.subscriptions');
    Route::get('reports/postulation_user_data', [ReportController::class, 'postulationDataIndex'])
        ->name('portal.reports.postulation_data');
    Route::get('reports/postulation_user_data/export', [ReportController::class, 'exportToExcelPostulationUsersData'])
        ->name('postulation_users_data.export');
    Route::delete('reports/destroy-postulation-data/{id}', [ReportController::class, 'destroyPostulationData'])
        ->name('destroy.postulation.data');
    Route::delete('reports/postulation_user_data/clean', [ReportController::class, 'cleanPostulationUsersData'])
        ->name('clean.postulation_user_data');
});
