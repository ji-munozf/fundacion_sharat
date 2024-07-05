<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller implements \Illuminate\Routing\Controllers\HasMiddleware

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

        // Filtra solo las vacantes activas y paginarlas
        $vacancies = Vacancy::where('active', true)->paginate();

        // Obtener las aplicaciones del usuario actual con sus respectivas vacantes
        $applications = Application::where('user_id', $user->id)->with('vacancy')->get()->keyBy('vacancy_id');

        // Verificar si el usuario tiene los roles 'Super admin' o 'Admin'
        $hasUnlimitedApplications = $user->hasRole('Super admin') || $user->hasRole('Admin');

        // Obtener el número de postulaciones del usuario actual en el mes actual si no tiene postulaciones ilimitadas
        $currentMonthApplications = $hasUnlimitedApplications ? 0 : Application::where('user_id', $user->id)
            ->whereMonth('created_at', date('m'))
            ->count();

        return view('portal.applications.index', compact('vacancies', 'applications', 'currentMonthApplications', 'hasUnlimitedApplications'));
    }

    /**
     * Enviar postulación de la vacante.
     */
    public function requestVacancy(Vacancy $vacancy)
    {
        return view('portal.applications.request_vacancy', compact('vacancy'));
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
        Application::create([
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

        return redirect()->route('portal.applications.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Application $application)
    {
        // Cargar la relación vacancy para mostrar detalles de la vacante si es necesario
        $application->load('vacancy');

        return view('portal.applications.edit', compact('application'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application)
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
            if ($application->curriculum_vitae) {
                Storage::disk('public')->delete($application->curriculum_vitae);
            }

            // Actualizar la ruta del archivo en el modelo
            $application->curriculum_vitae = $filePath;
        }

        // Actualizar los datos en la base de datos
        $application->update([
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

        return redirect()->route('portal.applications.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        // Verificar si la aplicación tiene un archivo de currículum vitae
        if ($application->curriculum_vitae) {
            // Eliminar el archivo del sistema de archivos
            Storage::disk('public')->delete($application->curriculum_vitae);
        }

        // Eliminar la aplicación de la base de datos
        $application->delete();

        // Crear el mensaje de éxito con el nombre de la vacante
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'La postulación a la vacante se canceló correctamente',
        ]);

        // Redirigir al usuario a la página principal
        return redirect()->route('portal.applications.index');
    }

}
