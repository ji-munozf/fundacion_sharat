<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Institution;
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
            // Mostrar todas las vacantes sin importar la institución
            $vacancies = Vacancy::with('institution')->paginate();
        } else {
            // Obtener la institución del usuario autenticado
            $institutionId = $user->institution_id;

            // Filtrar las vacantes por la institución del usuario
            $vacancies = Vacancy::where('institution_id', $institutionId)->with('institution')->paginate();
        }

        // Calcular el número de nuevas postulaciones para cada vacante
        foreach ($vacancies as $vacancy) {
            $newApplicationsCount = Application::where('vacancy_id', $vacancy->id)->count();
            $vacancy->newApplicationsCount = $newApplicationsCount;
        }

        return view('portal.vacancies.index', compact('vacancies'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $institutions = Institution::all();
        return view('portal.vacancies.create', compact('institutions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'description' => 'required|string',
            'contracting_manager' => 'required|string|max:255',
            'number_of_vacancies' => 'required|integer',
            'active' => 'required|boolean',
        ]);

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
        $applications = DB::table('applications')
            ->join('vacancies', 'applications.vacancy_id', '=', 'vacancies.id')
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->select('applications.*', 'vacancies.job_title as vacancy_title', 'users.name as user_name')
            ->where('applications.vacancy_id', $vacancy->id)
            ->get();

        return view('portal.vacancies.candidates', ['applications' => $applications, 'vacancy' => $vacancy]);
    }

    public function downloadCV($id)
    {
        $application = Application::find($id);

        if ($application && $application->curriculum_vitae) {
            $filePath = storage_path('app/public/' . $application->curriculum_vitae);

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacancy $vacancy)
    {
        try {
            // Guardar el nombre de la vacante antes de eliminarla
            $vacancyName = $vacancy->name;

            // Eliminar la vacante
            $vacancy->delete();

            // Crear un mensaje de éxito con el nombre de la vacante
            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'La vacante ' . $vacancyName . ' se eliminó correctamente.',
            ]);
        } catch (\Exception $e) {
            // Manejar cualquier error que ocurra durante la eliminación
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Hubo un problema al eliminar la vacante: ' . $e->getMessage(),
            ]);
        }

        // Redireccionar a la lista de vacantes
        return redirect()->route('portal.vacancies.index');
    }

}
