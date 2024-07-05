<?php

namespace App\Http\Controllers\Portal;

use App\Exports\PostulationsExport;
use App\Exports\PostulationUsersDataExport;
use App\Exports\SubscriptionsExport;
use App\Exports\VacanciesExport;
use App\Http\Controllers\Controller;
use App\Models\Postulation;
use App\Models\PostulationUserData;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller implements \Illuminate\Routing\Controllers\HasMiddleware

{
    public static function middleware(): array
    {
        return [
            (new Middleware(middleware: 'can:Visualizar reportes'))->only('index'),
            (new Middleware(middleware: 'can:Visualizar historial suscripciones'))->only('vacancyIndex'),
            (new Middleware(middleware: 'can:Visualizar historial vacantes'))->only('postulationIndex'),
            (new Middleware(middleware: 'can:Visualizar historial postulaciones'))->only('subscriptionIndex'),
            (new Middleware(middleware: 'can:Visualizar historial datos de postulación'))->only('postulationDataIndex'),
            (new Middleware(middleware: 'can:Exportar vacantes a excel'))->only('exportToExcelVacancies'),
            (new Middleware(middleware: 'can:Exportar postulaciones a excel'))->only('exportToExcelPostulations'),
            (new Middleware(middleware: 'can:Exportar suscripciones a excel'))->only('exportToExcelSubs'),
            (new Middleware(middleware: 'can:Exportar datos de postulación a excel'))->only('exportToExcelPostulationUsersData'),
            (new Middleware(middleware: 'can:Revertir eliminar vacante'))->only('revertirEliminarVacancy'),
            (new Middleware(middleware: 'can:Revertir eliminar postulación'))->only('revertirEliminarPostulation'),
            (new Middleware(middleware: 'can:Eliminar los datos de postulación del usuario'))->only('destroyPostulationData'),
            (new Middleware(middleware: 'can:Eliminar todas las vacantes'))->only('cleanVacancies'),
            (new Middleware(middleware: 'can:Eliminar todas las postulaciones'))->only('cleanPostulations'),
            (new Middleware(middleware: 'can:Eliminar todas las suscripciones'))->only('cleanSubscriptions'),
            (new Middleware(middleware: 'can:Eliminar todos los datos de postulación'))->only('cleanPostulationUsersData'),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('portal.reports.index');
    }

    public function vacancyIndex()
    {
        $vacancies = Vacancy::withCount('applications')->paginate();
        return view('portal.reports.vacancies', compact('vacancies'));
    }

    public function postulationIndex()
    {
        $postulations = Postulation::with('vacancy')->paginate();
        return view('portal.reports.postulations', compact('postulations'));
    }

    public function subscriptionIndex()
    {
        $subscriptions = Subscription::with('user')->paginate();
        return view('portal.reports.subscriptions', compact('subscriptions'));
    }

    public function postulationDataIndex()
    {
        $postulation_datas = PostulationUserData::paginate();
        return view('portal.reports.postulation_data', compact('postulation_datas'));
    }

    /**
     * Export tabla de vacantes a Excel
     */
    public function exportToExcelVacancies()
    {
        return Excel::download(new VacanciesExport, 'vacantes.xlsx');
    }

    /**
     * Export tabla de postulaciones a Excel
     */
    public function exportToExcelPostulations()
    {
        return Excel::download(new PostulationsExport, 'postulaciones.xlsx');
    }

    /**
     * Export tabla de suscripciones a Excel
     */
    public function exportToExcelSubs()
    {
        return Excel::download(new SubscriptionsExport, 'suscripciones.xlsx');
    }

    /**
     * Export tabla de datos de postulación a Excel
     */
    public function exportToExcelPostulationUsersData()
    {
        return Excel::download(new PostulationUsersDataExport, 'postulation_users_data.xlsx');
    }

    /**
     * Revertir eliminación de la vacante
     */
    public function revertirEliminarVacancy($id)
    {
        $vacancy = Vacancy::find($id);
        if ($vacancy) {
            $vacancy->is_eliminated = 0;
            $vacancy->active = 1;
            $vacancy->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Vacante no encontrada.']);
        }
    }

    /**
     * Revertir eliminación de la postulación
     */
    public function revertirEliminarPostulation($id)
    {
        $postulation = Postulation::find($id);
        if ($postulation) {
            $postulation->is_eliminated = 0;
            $postulation->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Postulación no encontrada.']);
        }
    }

    /**
     * Eliminar los datos de postulación del usuario
     */
    public function destroyPostulationData(Request $request, $user_id)
    {
        try {
            // Obtener todas las postulaciones del usuario
            $pendingPostulations = DB::table('postulation_status')
                ->join('postulations', 'postulation_status.postulation_id', '=', 'postulations.id')
                ->where('postulations.user_id', $user_id)
                ->whereNull('postulation_status.status')
                ->count();

            if ($pendingPostulations > 0) {
                session()->flash('swal', [
                    'icon' => 'error',
                    'title' => 'Operación no permitida',
                    'text' => 'No se puede eliminar los datos de postulación del usuario mientras tenga postulaciones en estado pendiente.',
                ]);

                return redirect()->route('portal.reports.postulation_data');
            }

            // Obtener los datos de postulación del usuario
            $postulationData = DB::table('postulation_user_data')
                ->where('user_id', $user_id)
                ->first();

            if ($postulationData) {
                // Eliminar el archivo del currículum
                if ($postulationData->curriculum_vitae && Storage::disk('public')->exists($postulationData->curriculum_vitae)) {
                    Storage::disk('public')->delete($postulationData->curriculum_vitae);
                }

                // Eliminar los datos de postulación del usuario
                DB::table('postulation_user_data')
                    ->where('user_id', $user_id)
                    ->delete();

                session()->flash('swal', [
                    'icon' => 'success',
                    'title' => '¡Bien hecho!',
                    'text' => 'Los datos de postulación se eliminaron correctamente',
                ]);

                return redirect()->route('portal.reports.postulation_data');
            } else {
                session()->flash('swal', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'Hubo un problema al encontrar los datos de postulación.',
                ]);

                return redirect()->route('portal.reports.postulation_data');
            }
        } catch (\Exception $e) {
            // Manejar cualquier excepción que ocurra
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'Hubo un problema al eliminar los datos de postulación: ' . $e->getMessage(),
            ]);

            return redirect()->route('portal.reports.postulation_data');
        }
    }

    /**
     * Eliminar todos los registros de la base de datos de la tabla Vacancy
     */
    public function cleanVacancies()
    {
        try {
            // Eliminar todas las postulaciones asociadas a vacantes
            DB::table('postulations')->delete();

            // Eliminar todas las vacantes
            Vacancy::query()->delete();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'Todas las vacantes y sus postulaciones asociadas fueron eliminadas exitosamente.',
            ]);

            // Redirigir con mensaje de éxito
            return redirect()->route('portal.reports.vacancies');
        } catch (\Exception $e) {
            // Redirigir con mensaje de error
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'Ocurrió un error al intentar eliminar las vacantes: ' . $e->getMessage(),
            ]);

            return redirect()->route('portal.reports.vacancies');
        }
    }

    /**
     * Eliminar todos los registros de la base de datos de la tabla Postulation
     */
    public function cleanPostulations()
    {
        try {
            // Eliminar todas las vacantes
            Postulation::query()->delete();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'Todas las postulaciones fueron eliminadas exitosamente.',
            ]);

            // Redirigir con mensaje de éxito
            return redirect()->route('portal.reports.postulations');
        } catch (\Exception $e) {
            // Redirigir con mensaje de error
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'Ocurrió un error al intentar eliminar las postulaciones: ' . $e->getMessage(),
            ]);

            return redirect()->route('portal.reports.postulations');
        }
    }

    /**
     * Eliminar todos los registros de la base de datos de la tabla Subscription
     */
    public function cleanSubscriptions()
    {
        try {
            // Obtener suscripciones no activas
            $inactiveSubscriptions = Subscription::where('end_date', '<', now())->get();

            // Eliminar imágenes de transferencias bancarias de suscripciones no activas
            foreach ($inactiveSubscriptions as $subscription) {
                Storage::disk('public')->delete($subscription->bank_transfer_snapshot);
            }

            // Eliminar las suscripciones no activas
            Subscription::where('end_date', '<', now())->delete();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'Las suscripciones no activas fueron eliminadas, los planes actualizados y las imágenes eliminadas exitosamente.',
            ]);

            // Redirigir con mensaje de éxito
            return redirect()->route('portal.reports.subscriptions');
        } catch (\Exception $e) {
            // Redirigir con mensaje de error
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'Ocurrió un error al intentar completar la acción: ' . $e->getMessage(),
            ]);

            return redirect()->route('portal.reports.subscriptions');
        }
    }

    /**
     * Eliminar todos los registros de la base de datos de la tabla PostulationUserData
     */
    public function cleanPostulationUsersData()
    {
        try {
            // Eliminar todas las imágenes almacenadas
            $images = Storage::allFiles('public/premium_user_curriculums'); // Ajusta el path según tu configuración
            Storage::delete($images);

            // Eliminar todas las suscripciones
            PostulationUserData::query()->delete();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'Todas los datos de postulación y las imágenes fueron eliminadas exitosamente.',
            ]);

            // Redirigir con mensaje de éxito
            return redirect()->route('portal.reports.postulation_data');
        } catch (\Exception $e) {
            // Redirigir con mensaje de error
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'Ocurrió un error al intentar completar la acción: ' . $e->getMessage(),
            ]);

            return redirect()->route('portal.reports.postulation_data');
        }
    }
}
