<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Postulation;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class PostulationController extends Controller implements \Illuminate\Routing\Controllers\HasMiddleware

{
    public static function middleware(): array
    {
        return [
            (new Middleware(middleware: 'can:Visualizar postulación'))->only('index'),
            (new Middleware(middleware: 'can:Crear postulación'))->only('requestVacancy'),
            (new Middleware(middleware: 'can:Editar postulación'))->only('edit'),
            (new Middleware(middleware: 'can:Cancelar postulación'))->only('destroy'),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // Obtener las aplicaciones del usuario actual con sus respectivas vacantes y estados
        $postulations = Postulation::where('user_id', $user->id)
            ->with(['vacancy', 'status'])
            ->get()
            ->keyBy('vacancy_id');

        // Filtrar vacantes activas e inactivas dependiendo del estado de la postulación del usuario
        $activeVacancies = Vacancy::where('active', true);
        $inactiveVacancies = Vacancy::where('active', false)
            ->whereIn('id', $postulations->whereIn('status.status', [0, 1])->pluck('vacancy_id'));

        // Unir ambas consultas
        $vacancies = $activeVacancies->union($inactiveVacancies)->paginate();

        // Verificar si el usuario tiene los roles 'Super admin' o 'Admin'
        $hasUnlimitedApplications = $user->hasRole('Super admin') || $user->hasRole('Admin');

        // Obtener el número de postulaciones del usuario actual en el mes actual si no tiene postulaciones ilimitadas
        $currentMonthApplications = $hasUnlimitedApplications ? 0 : Postulation::where('user_id', $user->id)
            ->whereMonth('created_at', date('m'))
            ->count();

        return view('portal.postulations.index', compact('vacancies', 'postulations', 'currentMonthApplications', 'hasUnlimitedApplications'));
    }

    /**
     * Enviar postulación de la vacante.
     */
    public function requestVacancy(Vacancy $vacancy)
    {
        return view('portal.postulations.request_vacancy', compact('vacancy'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function sendRequestVacancy(Request $request, Vacancy $vacancy)
    {
        // Validar los datos del formulario
        $request->validate([
            'names' => 'required|string|max:255',
            'last_names' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => ['required', 'regex:/^\+569[0-9]{8}$/', 'phone:CL,mobile'],
            'curriculum_vitae' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'fortalezas' => [
                'required',
                'string',
                'regex:/^(\w+)(,\s\w+){0,9}$/',
            ],
            'reasons' => 'required|string',
        ], [
            'contact_number.regex' => 'El número de contacto debe comenzar con +569 seguido de ocho dígitos.',
            'contact_number.phone' => 'El número de contacto no es un número de celular chileno válido.',
            'fortalezas.regex' => 'Debe ingresar máximo diez fortalezas, separadas por comas.',
        ]);

        // Manejo del archivo del currículum
        if ($request->hasFile('curriculum_vitae')) {
            $file = $request->file('curriculum_vitae');
            $originalName = $file->getClientOriginalName();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            // Verificar si el archivo ya existe y añadir un número si es necesario
            $filePath = "curriculums/{$originalName}";
            $counter = 1;
            while (Storage::disk('public')->exists($filePath)) {
                $filePath = "curriculums/{$fileName}_{$counter}.{$extension}";
                $counter++;
            }

            // Guardar el archivo en la ubicación final
            $file->storeAs('curriculums', basename($filePath), 'public');
        }

        // Guardar los datos en la base de datos
        Postulation::create([
            'names' => $request->input('names'),
            'last_names' => $request->input('last_names'),
            'email' => $request->input('email'),
            'contact_number' => $request->input('contact_number'),
            'curriculum_vitae' => $filePath ?? null, // Ruta del archivo guardado
            'strengths' => $request->input('fortalezas'),
            'reasons' => $request->input('reasons'),
            'vacancy_id' => $vacancy->id,
            'user_id' => auth()->user()->id, // ID del usuario autenticado
        ]);

        // Mensaje de éxito
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Se ha postulado a la vacante ' . $vacancy->name . ' correctamente.',
        ]);

        return redirect()->route('portal.postulations.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Postulation $postulation)
    {
        // Cargar la relación vacancy para mostrar detalles de la vacante si es necesario
        $postulation->load('vacancy');

        return view('portal.postulations.edit', compact('postulation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Postulation $postulation)
    {
        // Validar los datos del formulario
        $request->validate([
            'names' => 'required|string|max:255',
            'last_names' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => ['required', 'regex:/^\+569[0-9]{8}$/', 'phone:CL,mobile'],
            'curriculum_vitae' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'fortalezas' => [
                'required',
                'string',
                'regex:/^(\w+)(,\s\w+){0,9}$/',
            ],
            'reasons' => 'required|string',
        ], [
            'contact_number.regex' => 'El número de contacto debe comenzar con +569 seguido de ocho dígitos.',
            'contact_number.phone' => 'El número de contacto no es un número de celular chileno válido.',
            'fortalezas.regex' => 'Debe ingresar máximo diez fortalezas, separadas por comas.',
        ]);

        // Manejo del archivo del currículum
        if ($request->hasFile('curriculum_vitae')) {
            $file = $request->file('curriculum_vitae');
            $originalName = $file->getClientOriginalName();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            // Verificar si el archivo ya existe y añadir un número si es necesario
            $filePath = "curriculums/{$originalName}";
            $counter = 1;
            while (Storage::disk('public')->exists($filePath)) {
                $filePath = "curriculums/{$fileName}_{$counter}.{$extension}";
                $counter++;
            }

            // Guardar el archivo en la ubicación final
            $file->storeAs('curriculums', basename($filePath), 'public');

            // Borrar el archivo anterior si existe uno nuevo
            if ($postulation->curriculum_vitae) {
                Storage::disk('public')->delete($postulation->curriculum_vitae);
            }

            // Actualizar la ruta del archivo en el modelo
            $postulation->curriculum_vitae = $filePath;
        }

        // Actualizar los datos en la base de datos
        $postulation->update([
            'names' => $request->input('names'),
            'last_names' => $request->input('last_names'),
            'email' => $request->input('email'),
            'contact_number' => $request->input('contact_number'),
            'strengths' => $request->input('fortalezas'),
            'reasons' => $request->input('reasons'),
        ]);

        // Mensaje de éxito
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'La postulación se actualizó correctamente',
        ]);

        return redirect()->route('portal.postulations.index');
    }

    public function showReasons($postulationId)
    {
        $postulation = Postulation::with('status')->findOrFail($postulationId);

        if ($postulation->status) {
            $status = $postulation->status->status ? 'aceptada' : 'rechazada';
            $reasons = $postulation->status->reasons;

            return view('portal.postulations.reasons', compact('status', 'reasons'));
        }

        return redirect()->route('portal.postulations.index')->with('error', 'La postulación no tiene un estado definido.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Postulation $postulation)
    {
        // Verificar si la aplicación tiene un archivo de currículum vitae
        if ($postulation->curriculum_vitae) {
            // Eliminar el archivo del sistema de archivos
            Storage::disk('public')->delete($postulation->curriculum_vitae);
        }

        // Eliminar la aplicación de la base de datos
        $postulation->delete();

        // Crear el mensaje de éxito con el nombre de la vacante
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'La postulación a la vacante se canceló correctamente',
        ]);

        // Redirigir al usuario a la página principal
        return redirect()->route('portal.postulations.index');
    }

}
