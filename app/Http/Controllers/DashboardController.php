<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\User;
use App\Models\Vacancy;
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
            'totalActiveVacancies', // Pasar el número de vacantes activas a la vista
            'userInstitutionName', // Pasar el nombre de la institución del usuario a la vista
            'userInstitutionUserCount', // Pasar la cantidad de usuarios en la institución a la vista
            'userInstitutionVacancyCount', // Pasar la cantidad de vacantes creadas por la institución a la vista
            'postulationsCount'
        ));
    }

}
