<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\User;
use App\Models\Vacancy;
use Carbon\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user(); // Obtener el usuario logueado
        $userPlanName = $user->plan ? $user->plan->name : 'No asignado'; // Obtener el nombre del plan del usuario
        $subscription = $user->subscriptions()->latest()->first(); // Obtener la última suscripción del usuario

        $planDuration = $subscription ? $subscription->duration : 'Desconocida';
        $planEndDate = $subscription ? new Carbon($subscription->end_date) : null;

        // Configurar la localización en español para Carbon
        Carbon::setLocale('es');

        // Formatear la fecha con el nombre del mes en español y convertir la primera letra del mes a minúscula
        $planEndDateFormatted = $planEndDate ? $planEndDate->translatedFormat('d F Y \a \l\a\s H:i:s') : 'No disponible';

        // Convertir la primera letra del mes a minúscula
        $planEndDateFormatted = str_replace(
            ['A Las', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            ['a las', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
            $planEndDateFormatted
        );

        // Verificar el plan_id del usuario
        $planId = $user->plan_id;
        $showDetailedPlanInfo = $planId == 2;

        $institution = $user->institution; // Obtener la institución del usuario logueado

        $userInstitutionName = $institution ? $institution->name : 'No asignada';
        $userInstitutionUserCount = $institution ? $institution->users()->count() : 0;
        $userInstitutionVacancyCount = $institution ? $institution->vacancies()->count() : 0; // Obtener el número de vacantes creadas por la institución

        $totalUsers = User::count();
        $roles = Role::all();
        $totalRoles = $roles->count();
        $totalPermissions = Permission::count();
        $institutions = Institution::all();
        $totalInstitutions = $institutions->count();
        $totalVacancies = Vacancy::count();
        $totalActiveVacancies = Vacancy::where('active', true)->count(); // Obtener el número de vacantes activas

        // Obtener la cantidad de usuarios por rol
        $usersByRole = [];
        foreach ($roles as $role) {
            $usersByRole[$role->name] = $role->users()->count();
        }

        // Obtener la cantidad de usuarios por institución
        $usersByInstitution = [];
        foreach ($institutions as $institution) {
            $usersByInstitution[$institution->name] = $institution->users()->count();
        }

        // Obtener la cantidad de vacantes por institución excluyendo la institución con ID 1
        $vacanciesByInstitution = [];
        foreach ($institutions as $institution) {
            if ($institution->id != 1) {
                $vacanciesByInstitution[$institution->name] = $institution->vacancies()->count();
            }
        }

        // Contar las postulaciones del usuario actual
        $postulationsCount = $user->postulations()->count();

        return view('portal.dashboard', compact(
            'totalUsers',
            'usersByRole',
            'usersByInstitution',
            'vacanciesByInstitution',
            'totalRoles',
            'totalPermissions',
            'totalInstitutions',
            'totalVacancies',
            'totalActiveVacancies',
            'userInstitutionName',
            'userInstitutionUserCount',
            'userInstitutionVacancyCount',
            'postulationsCount',
            'userPlanName',
            'planDuration',
            'planEndDateFormatted',
            'showDetailedPlanInfo'
        ));
    }
}

