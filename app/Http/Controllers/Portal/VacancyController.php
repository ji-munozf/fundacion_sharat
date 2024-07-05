<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
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
        // Obtener la institución del usuario autenticado
        $institutionId = Auth::user()->institution_id;

        // Filtrar las vacantes por la institución del usuario y cargar las relaciones necesarias
        $vacancies = Vacancy::whereHas('user', function ($query) use ($institutionId) {
            $query->where('institution_id', $institutionId);
        })->with('user.institution')->paginate();

        return view('portal.vacancies.index', compact('vacancies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('portal.vacancies.create');
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

        // Crear la vacante
        $vacancy = Vacancy::create($validated);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El usuario ' . $vacancy->name . ' se creó correctamente.',
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
            'text' => 'El usuario ' . $vacancy->name . ' se actualizó correctamente.',
        ]);

        // Redireccionar a la lista de vacantes
        return redirect()->route('portal.vacancies.index');
    }

    /**
     * Visualizar candidatos.
     */
    public function candidates()
    {
        return view('portal.vacancies.candidates');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacancy $vacancy)
    {
        //
    }
}
