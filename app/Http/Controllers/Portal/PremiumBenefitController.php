<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\PostulationUserData;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class PremiumBenefitController extends Controller implements \Illuminate\Routing\Controllers\HasMiddleware

{
    public static function middleware(): array
    {
        return [
            (new Middleware(middleware: 'can:Acceder a datos de postulación'))->only('postulationData'),
            (new Middleware(middleware: 'checkPlanId:2'))->only('postulationData'),
            (new Middleware(middleware: 'can:Guardar datos de postulación'))->only('savePostulationData'),
            (new Middleware(middleware: 'can:Editar datos de postulación'))->only('editPostulationData'),
            (new Middleware(middleware: 'can:Eliminar datos de postulación'))->only('destroyPostulationData'),
        ];
    }

    public function postulationData()
    {
        $user = auth()->user();
        $postulationData = PostulationUserData::where('user_id', $user->id)->first();

        return view('portal.premium_benefits.postulation_data', compact('postulationData'));
    }

    public function savePostulationData(Request $request)
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
            $filePath = "premium_user_curriculums/{$originalName}";
            $counter = 1;
            while (Storage::disk('public')->exists($filePath)) {
                $filePath = "premium_user_curriculums/{$fileName}_{$counter}.{$extension}";
                $counter++;
            }

            // Guardar el archivo en la ubicación final
            $file->storeAs('premium_user_curriculums', basename($filePath), 'public');
        }

        // Guardar los datos en la base de datos
        PostulationUserData::create([
            'names' => $request->input('names'),
            'last_names' => $request->input('last_names'),
            'email' => $request->input('email'),
            'contact_number' => $request->input('contact_number'),
            'curriculum_vitae' => $filePath ?? null, // Ruta del archivo guardado
            'strengths' => $request->input('fortalezas'),
            'reasons' => $request->input('reasons'),
            'user_id' => auth()->user()->id, // ID del usuario autenticado
        ]);

        // Mensaje de éxito
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Sus datos de postulación se guardaron correctamente.',
        ]);

        return redirect()->route('portal.premium_benefits.postulation_data');
    }

    public function editPostulationData(PostulationUserData $postulationUserData)
    {
        // Extraer solo el nombre del archivo de la ruta
        $postulationUserData->file_name = basename($postulationUserData->curriculum_vitae);

        return view('portal.premium_benefits.edit_postulation_data', compact('postulationUserData'));
    }

    public function updatePostulationData(Request $request, PostulationUserData $postulationUserData)
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
            $filePath = "premium_user_curriculums/{$originalName}";
            $counter = 1;
            while (Storage::disk('public')->exists($filePath)) {
                $filePath = "premium_user_curriculums/{$fileName}_{$counter}.{$extension}";
                $counter++;
            }

            // Guardar el archivo en la ubicación final
            $file->storeAs('premium_user_curriculums', basename($filePath), 'public');

            // Borrar el archivo anterior si existe uno nuevo
            if ($postulationUserData->curriculum_vitae) {
                Storage::disk('public')->delete($postulationUserData->curriculum_vitae);
            }

            // Actualizar la ruta del archivo en el modelo
            $postulationUserData->curriculum_vitae = $filePath;
        }

        // Actualizar los datos en la tabla postulation_user_data
        $postulationUserData->update([
            'names' => $request->input('names'),
            'last_names' => $request->input('last_names'),
            'email' => $request->input('email'),
            'contact_number' => $request->input('contact_number'),
            'strengths' => $request->input('fortalezas'),
            'reasons' => $request->input('reasons'),
        ]);

        // Actualizar los datos en la tabla postulations si el usuario tiene postulaciones
        $userId = $postulationUserData->user_id;
        $postulations = \App\Models\Postulation::where('user_id', $userId)->get();

        foreach ($postulations as $postulation) {
            $postulation->update([
                'names' => $request->input('names'),
                'last_names' => $request->input('last_names'),
                'email' => $request->input('email'),
                'contact_number' => $request->input('contact_number'),
                'curriculum_vitae' => $postulationUserData->curriculum_vitae, // Asegúrate de que sea la misma ruta del archivo actualizado
                'strengths' => $request->input('fortalezas'),
                'reasons' => $request->input('reasons'),
            ]);
        }

        // Mensaje de éxito
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Los datos de postulación se actualizaron correctamente',
        ]);

        return redirect()->route('portal.premium_benefits.postulation_data');
    }

}
