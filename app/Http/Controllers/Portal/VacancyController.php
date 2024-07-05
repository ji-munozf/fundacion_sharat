<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

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

        // Verificar si el usuario tiene los roles de Super admin o Admin
        if ($user->hasRole(['Super admin', 'Admin'])) {
            // Mostrar todas las vacantes sin importar la institución
            $vacancies = Vacancy::with('institution')->paginate();
        } else {
            // Obtener la institución del usuario autenticado
            $institutionId = $user->institution_id;

            // Filtrar las vacantes por la institución del usuario
            $vacancies = Vacancy::where('institution_id', $institutionId)->with('institution')->paginate();
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
    public function vacanciesPosted(Vacancy $vacancy)
    {
        // Filtra solo las vacantes activas
        $vacancies = Vacancy::where('active', true)->paginate();
        return view('portal.vacancies.vacancies_posted', compact('vacancies'));
    }

    public function requestVacancy(Vacancy $vacancy)
    {
        return view('portal.vacancies.request_vacancy', compact('vacancy'));
    }

    public function sendRequestVacancy(Vacancy $vacancy)
    {

    }

    public function candidates()
    {
        return view('portal.vacancies.candidates');
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
