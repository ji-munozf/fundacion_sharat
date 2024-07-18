<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckSubscriptions extends Command
{
    protected $signature = 'subscriptions:check';
    protected $description = 'Check and update expired subscriptions';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Crear un cliente HTTP
        $client = new Client();
        $response = $client->get('http://worldtimeapi.org/api/timezone/America/Santiago');

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            $now = Carbon::parse($data['datetime']);
        } else {
            // Manejar el error si la solicitud falla
            $this->error('No se pudo obtener la hora actual de la API.');
            Log::error('No se pudo obtener la hora actual de la API.');
            return;
        }

        // Obtener todas las suscripciones expiradas
        $expiredSubscriptions = DB::table('subscriptions')
            ->where('end_date', '<=', $now)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('users')
                    ->whereColumn('users.id', 'subscriptions.user_id')
                    ->where('users.plan_id', 1);
            })
            ->get();

        foreach ($expiredSubscriptions as $subscription) {
            // Actualizar el plan del usuario a Básico (id = 1)
            DB::table('users')
                ->where('id', $subscription->user_id)
                ->update(['plan_id' => 1]);
            Log::info('Plan del usuario actualizado a Básico para el usuario con ID: ' . $subscription->user_id);
        }

        $this->info('Se han comprobado y actualizado las suscripciones caducadas.');
        Log::info('Se han comprobado y actualizado las suscripciones caducadas.');
    }
}
