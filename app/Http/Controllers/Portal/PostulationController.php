<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Postulation;
use App\Models\PostulationStatus;
use App\Models\PostulationUserData;
use App\Models\Vacancy;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use \App\Rules\Recaptcha;

class PostulationController extends Controller implements \Illuminate\Routing\Controllers\HasMiddleware

{
    public static function middleware(): array
    {
        return [
            (new Middleware(middleware: 'can:Visualizar postulación'))->only('index'),
            (new Middleware(middleware: 'can:Crear postulación'))->only('requestVacancy'),
            (new Middleware(middleware: 'can:Editar postulación'))->only('edit'),
            (new Middleware(middleware: 'can:Cancelar postulación'))->only('cancelPostulation'),
            (new Middleware(middleware: 'can:Eliminar postulación'))->only('destroy'),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // Verificar si el usuario tiene el rol de "Postulante" y plan_id 2
        $isPostulantWithPlan2 = $user->hasRole('Postulante') && $user->plan_id == 2;

        // Obtener las aplicaciones del usuario actual con sus respectivas vacantes y estados, excluyendo las eliminadas
        $postulations = Postulation::where('user_id', $user->id)
            ->where('is_eliminated', 0)
            ->with(['vacancy', 'status'])
            ->get()
            ->keyBy('vacancy_id');

        // Obtener IDs de las vacantes que tienen postulaciones eliminadas
        $eliminatedPostulationsVacancyIds = Postulation::where('user_id', $user->id)
            ->where('is_eliminated', 1)
            ->pluck('vacancy_id')
            ->toArray();

        // Filtrar vacantes activas e inactivas dependiendo del estado de la postulación del usuario
        $activeVacancies = Vacancy::where('active', true)
            ->whereNotIn('id', $eliminatedPostulationsVacancyIds);

        $inactiveVacancies = Vacancy::where('active', false)
            ->whereNotIn('id', $eliminatedPostulationsVacancyIds)
            ->whereIn('id', $postulations->whereIn('status.status', [0, 1])->pluck('vacancy_id'));

        // Unir ambas consultas
        $vacancies = $activeVacancies->union($inactiveVacancies)->paginate();

        // Verificar si el usuario tiene los roles 'Super admin' o 'Admin' o si es "Postulante" con plan_id 2
        $hasUnlimitedApplications = $user->hasRole('Super admin') || $user->hasRole('Admin') || $isPostulantWithPlan2;

        // Obtener la fecha y hora actual en Santiago
        $client = new Client();
        $response = $client->get('http://worldtimeapi.org/api/timezone/America/Santiago');
        $data = json_decode($response->getBody()->getContents(), true);
        $currentDateTime = Carbon::parse($data['datetime']);

        // Obtener el número de postulaciones del usuario actual en el mes actual si no tiene postulaciones ilimitadas
        $currentMonthApplications = $hasUnlimitedApplications ? 0 : Postulation::where('user_id', $user->id)
            ->whereYear('created_at', $currentDateTime->year)
            ->whereMonth('created_at', $currentDateTime->month)
            ->count();

        return view('portal.postulations.index', compact('vacancies', 'postulations', 'currentMonthApplications', 'hasUnlimitedApplications', 'isPostulantWithPlan2'));
    }

    /**
     * Enviar postulación de la vacante.
     */
    public function requestVacancy(Vacancy $vacancy)
    {
        $user = auth()->user();
        $postulationDataAvailable = PostulationUserData::where('user_id', $user->id)->exists();
        $postulations = Postulation::where('user_id', $user->id)->with('status')->get()->keyBy('vacancy_id');
        return view('portal.postulations.request_vacancy', compact('vacancy', 'postulationDataAvailable', 'postulations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function sendRequestVacancy(Request $request, Vacancy $vacancy)
    {
        $request->validate([
            'names' => 'required|string|max:255',
            'last_names' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => ['required', 'regex:/^\+569[0-9]{8}$/', 'phone:CL,mobile'],
            'curriculum_vitae' => 'required_if:autofill,0|file|mimes:pdf,doc,docx|max:2048',
            'fortalezas' => [
                'required',
                'string',
                'regex:/^(\w+)(,\s\w+){0,9}$/',
            ],
            'reasons' => 'required|string',
            'autofill' => 'boolean',
            'g-recaptcha-response' => ['required', new Recaptcha],
        ], [
            'curriculum_vitae.required_if' => 'El campo curriculum vitae es obligatorio.',
            'contact_number.regex' => 'El número de contacto debe comenzar con +569 seguido de ocho dígitos.',
            'contact_number.phone' => 'El número de contacto no es un número de celular chileno válido.',
            'fortalezas.regex' => 'Debe ingresar máximo diez fortalezas, separadas por comas.',
        ]);

        if ($request->hasFile('curriculum_vitae')) {
            $file = $request->file('curriculum_vitae');
            $originalName = $file->getClientOriginalName();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            $user = auth()->user();
            $directory = $user->plan_id == 2 ? 'premium_user_curriculums' : 'curriculums';
            $filePath = "{$directory}/{$originalName}";
            $counter = 1;

            while (Storage::disk('public')->exists($filePath)) {
                $filePath = "{$directory}/{$fileName}_{$counter}.{$extension}";
                $counter++;
            }

            $storedFilePath = $file->storeAs($directory, basename($filePath), 'public');
        } else {
            $userData = PostulationUserData::where('user_id', auth()->user()->id)->first();
            $storedFilePath = $userData->curriculum_vitae ?? null;
        }

        if (!$storedFilePath) {
            return back()->withErrors(['curriculum_vitae' => 'Debe proporcionar un currículum vitae.']);
        }

        $postulation = Postulation::create([
            'names' => $request->input('names'),
            'last_names' => $request->input('last_names'),
            'email' => $request->input('email'),
            'contact_number' => $request->input('contact_number'),
            'curriculum_vitae' => $storedFilePath,
            'strengths' => $request->input('fortalezas'),
            'reasons' => $request->input('reasons'),
            'vacancy_id' => $vacancy->id,
            'user_id' => auth()->user()->id,
        ]);

        // Agregar el registro en postulation_status con status y reasons como null
        PostulationStatus::create([
            'postulation_id' => $postulation->id,
            'status' => null,
            'reasons' => null,
        ]);

        // Mensaje de éxito
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Se ha postulado a la vacante ' . $vacancy->name . ' correctamente.',
        ]);

        return redirect()->route('portal.postulations.index');
    }

    public function getUserData(Request $request)
    {
        if (auth()->user()->plan_id != 2) {
            return response()->json([], 403);
        }

        $userData = PostulationUserData::where('user_id', auth()->id())->first();
        if ($userData) {
            return response()->json($userData);
        } else {
            return response()->json([], 404);
        }
    }

    public function saveSelected(Request $request)
    {
        $user = auth()->user();
        $selectedVacancies = $request->input('selected_vacancies', []);

        // Obtener los datos del usuario logueado de la tabla postulation_user_data
        $userData = PostulationUserData::where('user_id', $user->id)->first();

        if (!$userData) {
            return response()->json([
                'icon' => 'warning',
                'title' => 'Datos de postulación faltantes',
                'text' => 'Debe primero agregar sus datos de postulación para usar esta función.',
                'redirect_url' => route('portal.premium_benefits.postulation_data'),
            ]);
        }

        foreach ($selectedVacancies as $vacancyId) {
            // Crear una nueva postulación
            $postulation = Postulation::create([
                'names' => $userData->names,
                'last_names' => $userData->last_names,
                'email' => $userData->email,
                'contact_number' => $userData->contact_number,
                'curriculum_vitae' => $userData->curriculum_vitae,
                'strengths' => $userData->strengths,
                'reasons' => $userData->reasons,
                'vacancy_id' => $vacancyId,
                'user_id' => $user->id,
            ]);

            // Crear el estado de la postulación con status y reasons como null
            PostulationStatus::create([
                'status' => null,
                'reasons' => null,
                'postulation_id' => $postulation->id,
            ]);
        }

        return response()->json(['message' => 'Se han postulado correctamente a las vacantes seleccionadas.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Postulation $postulation)
    {
        // Cargar la relación vacancy para mostrar detalles de la vacante si es necesario
        $postulation->load('vacancy');

        // Extraer solo el nombre del archivo de la ruta
        $postulation->file_name = basename($postulation->curriculum_vitae);

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

        // Obtener el usuario logeado
        $user = auth()->user();

        // Determinar la carpeta de almacenamiento en función del plan_id
        $folder = $user->plan_id == 2 ? 'premium_user_curriculums' : 'curriculums';

        // Manejo del archivo del currículum
        if ($request->hasFile('curriculum_vitae')) {
            $file = $request->file('curriculum_vitae');
            $originalName = $file->getClientOriginalName();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            // Verificar si el archivo ya existe y añadir un número si es necesario
            $filePath = "{$folder}/{$originalName}";
            $counter = 1;
            while (Storage::disk('public')->exists($filePath)) {
                $filePath = "{$folder}/{$fileName}_{$counter}.{$extension}";
                $counter++;
            }

            // Guardar el archivo en la ubicación final
            $file->storeAs($folder, basename($filePath), 'public');

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

    public function cancelPostulation(Postulation $postulation)
    {
        // Obtener el usuario que hizo la postulación
        $user = $postulation->user;

        // Verificar si la aplicación tiene un archivo de currículum vitae
        if ($postulation->curriculum_vitae) {
            // Verificar si el usuario tiene plan_id 1 y si el currículum vitae está en la carpeta 'curriculums'
            if ($user->plan_id == 1 && strpos($postulation->curriculum_vitae, 'curriculums/') === 0) {
                // Eliminar el archivo del sistema de archivos
                Storage::disk('public')->delete($postulation->curriculum_vitae);
            }

            // Verificar si el usuario tiene plan_id 2
            if ($user->plan_id == 2) {
                // Obtener los datos de postulación del usuario
                $postulationUserData = PostulationUserData::where('user_id', $user->id)->first();

                // Verificar si el currículum de la postulación no coincide con el guardado en postulation_user_data
                if ($postulationUserData && $postulation->curriculum_vitae !== $postulationUserData->curriculum_vitae) {
                    // Verificar si el currículum vitae está en la carpeta 'premium_user_curriculums'
                    if (strpos($postulation->curriculum_vitae, 'premium_user_curriculums/') === 0) {
                        // Eliminar el archivo del sistema de archivos
                        Storage::disk('public')->delete($postulation->curriculum_vitae);
                    }
                }
            }
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Postulation $postulation)
    {
        // Actualizar el campo 'is_eliminated' a 1
        $postulation->update(['is_eliminated' => 1]);

        // Crear el mensaje de éxito con el nombre de la vacante
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'La vacate que se postuló se eliminó correctamente',
        ]);

        // Redirigir al usuario a la página principal
        return redirect()->route('portal.postulations.index');
    }

}
