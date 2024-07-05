<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use GuzzleHttp\Client;

class CheckSubscriptions
{
    public function handle($request, Closure $next)
    {
        // Obtiene la hora actual desde un servicio externo
        $client = new Client();
        $response = $client->get('http://worldtimeapi.org/api/timezone/America/Santiago');
        $data = json_decode($response->getBody(), true);

        // Asegúrate de manejar errores en la respuesta
        if (!isset($data['datetime'])) {
            return response()->json(['error' => 'Unable to get the current time'], 500);
        }

        // Convierte la hora externa a un objeto Carbon
        $now = Carbon::parse($data['datetime']);

        // Actualiza las suscripciones expiradas
        DB::table('users')
            ->join('subscriptions', 'users.id', '=', 'subscriptions.user_id')
            ->where('subscriptions.end_date', '<', $now)
            ->where('users.plan_id', '!=', 1)
            ->update(['users.plan_id' => 1]);

        return $next($request);
    }
}
