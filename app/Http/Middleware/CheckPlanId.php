<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckPlanId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $planId
     * @return mixed
     */
    public function handle(Request $request, Closure $next, int $planId)
    {
        if (Auth::check() && Auth::user()->plan_id == $planId) {
            return $next($request);
        }

        // Lanza una excepción 403 si el usuario no tiene el plan_id correcto
        throw new HttpException(403, 'No tiene el plan adecuado.');
    }
}

