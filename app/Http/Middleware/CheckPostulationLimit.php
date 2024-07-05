<?php

namespace App\Http\Middleware;

use App\Models\Postulation;
use Carbon\Carbon;
use Closure;
use GuzzleHttp\Client;

class CheckPostulationLimit
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        $vacancyId = $request->route('vacancy')->id;

        // Get the current date and time in Santiago using the API
        $client = new Client();
        $response = $client->get('http://worldtimeapi.org/api/timezone/America/Santiago');
        $data = json_decode($response->getBody()->getContents(), true);
        $currentDateTime = Carbon::parse($data['datetime']);

        // Check if the user has already applied to this vacancy
        $alreadyApplied = Postulation::where('user_id', $user->id)
            ->where('vacancy_id', $vacancyId)
            ->exists();

        if ($alreadyApplied) {
            abort(403, 'Ya has postulado a esta vacante.');
        }

        // Count the user's postulations for the current month
        $currentMonthApplications = Postulation::where('user_id', $user->id)
            ->whereYear('created_at', $currentDateTime->year)
            ->whereMonth('created_at', $currentDateTime->month)
            ->count();

        // If the user has already applied to two vacancies, and has plan_id of 1, abort with a 403 error
        if ($currentMonthApplications >= 2 && $user->plan_id == 1) {
            abort(403, 'Has alcanzado el límite de dos postulaciones por mes.');
        }

        return $next($request);
    }
}
