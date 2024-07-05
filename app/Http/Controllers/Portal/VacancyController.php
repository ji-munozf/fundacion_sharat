<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Postulation;
use App\Models\PostulationStatus;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VacancyController extends Controller implements \Illuminate\Routing\Controllers\HasMiddleware

{
    public static function middleware(): array
    {
        return [
            (new Middleware(middleware: 'can:Visualizar vacantes'))->only('index'),
            (new Middleware(middleware: 'can:Crear vacantes'))->only('create'),
            (new Middleware(middleware: 'can:Actualizar vacantes'))->only('edit'),
            (new Middleware(middleware: 'can:Eliminar vacantes'))->only('destroy'),
            (new Middleware(middleware: 'can:Visualizar postulantes'))->only('candidates'),
            (new Middleware(middleware: 'can:Descargar CV'))->only('downloadCV'),
            (new Middleware(middleware: 'can:Visualizar botón aceptar'))->only('showAcceptForm'),
            (new Middleware(middleware: 'can:Visualizar botón rechazar'))->only('showRejectForm'),
            (new Middleware(middleware: 'can:Actualizar razones'))->only('editReasonsForm'),
            (new Middleware(middleware: 'can:Cancelar elección'))->only('cancelPostulation'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener todas las vacantes
        if ($user->hasRole(['Super admin', 'Admin'])) {
            // Mostrar todas las vacantes sin importar la institución, pero filtrando por is_eliminated = 0
            $vacancies = Vacancy::with('institution')
                ->where('is_eliminated', 0)
                ->paginate();
        } else {
            // Obtener la institución del usuario autenticado
            $institutionId = $user->institution_id;

            // Filtrar las vacantes por la institución del usuario y is_eliminated = 0
            $vacancies = Vacancy::where('institution_id', $institutionId)
                ->where('is_eliminated', 0)
                ->with('institution')
                ->paginate();
        }

        // Calcular el número de nuevas postulaciones y postulaciones pendientes para cada vacante
        foreach ($vacancies as $vacancy) {
            $newApplicationsCount = Postulation::where('vacancy_id', $vacancy->id)->count();
            $vacancy->newApplicationsCount = $newApplicationsCount;

            $pendingApplicationsCount = Postulation::where('vacancy_id', $vacancy->id)
                ->leftJoin('postulation_status', 'postulations.id', '=', 'postulation_status.postulation_id')
                ->whereNull('postulation_status.status')
                ->count();
            $vacancy->pendingApplicationsCount = $pendingApplicationsCount;
        }

        return view('portal.vacancies.index', compact('vacancies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener todas las instituciones excepto la que tiene id 1
        $institutions = Institution::where('id', '!=', 1)->get();
        return view('portal.vacancies.create', compact('institutions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $rules = [
            'name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'description' => 'required|string',
            'contracting_manager' => 'required|string|max:255',
            'number_of_vacancies' => 'required|integer',
            'gross_salary' => 'required|integer',
            'active' => 'required|boolean',
        ];

        if (Auth::user()->hasRole(['Super admin', 'Admin'])) {
            $rules['institution_id'] = 'required|exists:institutions,id';
        }

        $validated = $request->validate($rules);

        // Asegúrate de que el campo 'active' se maneja correctamente
        if (!isset($validated['active'])) {
            $validated['active'] = 0;
        }

        // Agregar la ID del usuario autenticado
        $validated['user_id'] = Auth::id();

        // Verificar si el usuario tiene roles Super admin o Admin
        if (Auth::user()->hasRole(['Super admin', 'Admin'])) {
            // Si tiene los roles, agregar la institución seleccionada en el formulario
            $validated['institution_id'] = $request->input('institution_id');
        } else {
            // Si no tiene los roles, agregar la institución del usuario autenticado
            $validated['institution_id'] = Auth::user()->institution_id;
        }

        // Crear la vacante
        $vacancy = Vacancy::create($validated);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'La vacante ' . $vacancy->name . ' se creó correctamente.',
        ]);

        // Redireccionar a la lista de vacantes
        return redirect()->route('portal.vacancies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vacancy $vacancy)
    {
        return view('portal.vacancies.show', compact('vacancy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vacancy $vacancy)
    {
        return view('portal.vacancies.edit', compact('vacancy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vacancy $vacancy)
    {
        // Validar los datos de entrada
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'description' => 'required|string',
            'contracting_manager' => 'required|string|max:255',
            'number_of_vacancies' => 'required|integer',
            'gross_salary' => 'required|integer',
            'active' => 'nullable|boolean',
        ]);

        // Asegúrate de que el campo 'active' se maneja correctamente
        if (!isset($validated['active'])) {
            $validated['active'] = 0;
        }

        $vacancy->update($validated);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'La vacante ' . $vacancy->name . ' se actualizó correctamente.',
        ]);

        // Redireccionar a la lista de vacantes
        return redirect()->route('portal.vacancies.index');
    }

    /**
     * Visualizar candidatos.
     */
    public function candidates(Vacancy $vacancy)
    {
        $query = DB::table('postulations')
            ->leftJoin('postulation_status', 'postulations.id', '=', 'postulation_status.postulation_id')
            ->join('vacancies', 'postulations.vacancy_id', '=', 'vacancies.id')
            ->join('users', 'postulations.user_id', '=', 'users.id')
            ->select(
                'postulations.*',
                'vacancies.name as vacancy_name',
                'vacancies.number_of_vacancies',
                'vacancies.is_eliminated_postulant',
                'users.name as user_name',
                'postulation_status.status as postulation_status',
                'postulation_status.reasons as postulation_reasons'
            )
            ->where('postulations.vacancy_id', $vacancy->id);

        if (!auth()->user()->hasRole('Super admin') && !auth()->user()->hasRole('Admin')) {
            $query->where('vacancies.is_eliminated_postulant', false);
        }

        $postulations = $query->get();

        return view('portal.vacancies.candidates', [
            'postulations' => $postulations,
            'vacancy' => $vacancy,
        ]);
    }

    public function downloadCV($id)
    {
        $postulation = Postulation::find($id);

        if ($postulation && $postulation->curriculum_vitae) {
            $filePath = storage_path('app/public/' . $postulation->curriculum_vitae);

            if (file_exists($filePath)) {
                return response()->download($filePath, basename($filePath), [
                    'Content-Type' => mime_content_type($filePath),
                ]);
            } else {
                return redirect()->back()->with('error', 'El currículum vitae no se encontró.');
            }
        } else {
            return redirect()->back()->with('error', 'El currículum vitae no se encontró.');
        }
    }

    public function showAcceptForm(Postulation $postulation)
    {
        $vacancy = $postulation->vacancy; // Asegúrate de obtener la vacante relacionada

        return view('portal.vacancies.accept', [
            'postulation' => $postulation,
            'vacancy' => $vacancy, // Pasa la vacante a la vista
        ]);
    }

    public function showRejectForm(Postulation $postulation)
    {
        $vacancy = $postulation->vacancy; // Asegúrate de obtener la vacante relacionada

        return view('portal.vacancies.reject', [
            'postulation' => $postulation,
            'vacancy' => $vacancy, // Pasa la vacante a la vista
        ]);
    }

    public function acceptPostulation(Request $request, Postulation $postulation)
    {
        $request->validate([
            'razones' => 'required|string',
        ]);

        // Buscar si ya existe un status para esta postulación
        $postulationStatus = PostulationStatus::where('postulation_id', $postulation->id)->first();

        if ($postulationStatus) {
            // Si existe, actualizar el registro
            $postulationStatus->status = true;
            $postulationStatus->reasons = $request->razones;
        } else {
            // Si no existe, crear un nuevo registro
            $postulationStatus = new PostulationStatus();
            $postulationStatus->postulation_id = $postulation->id;
            $postulationStatus->status = true;
            $postulationStatus->reasons = $request->razones;
        }
        $postulationStatus->save();

        $vacancy = $postulation->vacancy;
        $vacancy->number_of_vacancies -= 1;
        if ($vacancy->number_of_vacancies <= 0) {
            $vacancy->active = false;
        }
        $vacancy->save();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Postulación aceptada',
        ]);

        // Redireccionar a la lista de vacantes
        return redirect()->route('portal.vacancies.candidates', $postulation->vacancy_id);
    }

    public function rejectPostulation(Request $request, Postulation $postulation)
    {
        $request->validate([
            'razones' => 'required|string',
        ]);

        // Buscar si ya existe un status para esta postulación
        $postulationStatus = PostulationStatus::where('postulation_id', $postulation->id)->first();

        if ($postulationStatus) {
            // Si existe, actualizar el registro
            $postulationStatus->status = false;
            $postulationStatus->reasons = $request->razones;
        } else {
            // Si no existe, crear un nuevo registro
            $postulationStatus = new PostulationStatus();
            $postulationStatus->postulation_id = $postulation->id;
            $postulationStatus->status = false;
            $postulationStatus->reasons = $request->razones;
        }
        $postulationStatus->save();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Postulación rechazada',
        ]);

        // Redireccionar a la lista de vacantes
        return redirect()->route('portal.vacancies.candidates', $postulation->vacancy_id);
    }

    public function editReasonsForm(Postulation $postulation)
    {
        $vacancy = $postulation->vacancy;
        $postulationStatus = PostulationStatus::where('postulation_id', $postulation->id)->first();
        return view('portal.vacancies.edit-reasons', [
            'postulation' => $postulation,
            'vacancy' => $vacancy,
            'postulationStatus' => $postulationStatus,
        ]);
    }

    public function updateReasons(Request $request, Postulation $postulation)
    {
        $request->validate([
            'reasons' => 'required|string',
        ]);

        $postulationStatus = PostulationStatus::where('postulation_id', $postulation->id)->first();
        $postulationStatus->reasons = $request->reasons;
        $postulationStatus->save();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Razón actualizada',
        ]);

        // Redireccionar a la lista de vacantes
        return redirect()->route('portal.vacancies.candidates', $postulation->vacancy_id);
    }

    /**
     * Cancelar la elección de la postulación
     */
    public function cancelPostulation($id)
    {
        $postulation = Postulation::findOrFail($id);
        $postulationStatus = PostulationStatus::where('postulation_id', $id)->first();

        if ($postulationStatus) {
            // Verificar si la postulación estaba aceptada o rechazada
            $wasAccepted = $postulationStatus->status;

            // Actualizar el estado de la postulación a null
            $postulationStatus->status = null;
            $postulationStatus->reasons = null;
            $postulationStatus->save();

            // Si la postulación estaba aceptada, ajustar la vacante
            if ($wasAccepted) {
                $vacancy = $postulation->vacancy;
                $vacancy->number_of_vacancies += 1;
                $vacancy->active = true;
                $vacancy->save();
            }

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'Postulación cancelada',
            ]);
        }

        return redirect()->route('portal.vacancies.candidates', $postulation->vacancy_id);
    }

    /**
     * Verificar si el usuario postulante elimino su postulación
     */
    public function checkPostulationEliminated($id)
    {
        $postulation = Postulation::with('vacancy')->findOrFail($id);
        return response()->json([
            'is_eliminated' => $postulation->is_eliminated,
            'vacancy_name' => $postulation->vacancy->name,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacancy $vacancy)
    {
        DB::beginTransaction();

        try {
            // Obtener postulaciones pendientes
            $pendingPostulations = DB::table('postulations')
                ->leftJoin('postulation_status', 'postulations.id', '=', 'postulation_status.postulation_id')
                ->where('postulations.vacancy_id', $vacancy->id)
                ->whereNull('postulation_status.status')
                ->select('postulations.id')
                ->get();

            // Actualizar el estado de las postulaciones pendientes
            foreach ($pendingPostulations as $postulation) {
                DB::table('postulation_status')->updateOrInsert(
                    ['postulation_id' => $postulation->id],
                    [
                        'status' => 0,
                        'reasons' => 'La institución ha decidido eliminar la vacante a la cual usted se postuló',
                    ]
                );
            }

            // Actualizar la vacante para marcarla como eliminada
            $vacancy->is_eliminated = 1;
            $vacancy->active = 0;
            $vacancy->save();

            DB::commit();

            // Crear un mensaje de éxito con el nombre de la vacante
            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'La vacante ' . $vacancy->name . ' se eliminó correctamente.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Manejar cualquier error que ocurra durante la actualización
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Hubo un problema al eliminar la vacante: ' . $vacancy->name . '. ' . $e->getMessage(),
            ]);
        }

        // Redireccionar a la lista de vacantes
        return redirect()->route('portal.vacancies.index');
    }

    /**
     * "Eliminar" al postulante del listado de postulaciones de la vacante.
     */
    public function destroyPostulante($id)
    {
        $postulation = Postulation::findOrFail($id);
        $vacancy = $postulation->vacancy;

        $vacancy->is_eliminated_postulant = true;
        $vacancy->save();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El postulante se eliminó correctamente',
        ]);

        return redirect()->route('portal.vacancies.candidates', $vacancy->id);
    }

    /**
     * Revertir "eliminar" al postulante del listado de postulaciones de la vacante.
     */
    public function revertirDestroyPostulante(Vacancy $vacancy)
    {
        // Cambiar el campo is_eliminated_postulant a false
        $vacancy->is_eliminated_postulant = false;
        $vacancy->save();

        // Mensaje de éxito
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Se revirtió la eliminación del postulante',
        ]);

        return redirect()->route('portal.vacancies.candidates', $vacancy->id);
    }

}
