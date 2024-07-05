<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticateUser
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        $user = Auth::user();

        if ($user->hasRole('Institución') && $user->institution && !$user->institution->is_active) {
            Auth::logout();

            throw ValidationException::withMessages([
                'institution' => 'Su institución está desactivada. No puede iniciar sesión.',
            ]);
        }

        // No redirigir aquí, simplemente retornar el usuario autenticado
        return $user;
    }
}
