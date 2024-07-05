<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Malahierba\ChileRut\ChileRut;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'rut' => ['required', 'string', 'unique:users', function ($attribute, $value, $fail) {
                try {
                    $chileRut = new ChileRut();

                    // Verifica si el RUT es válido
                    if (!$chileRut->check($value)) {
                        return $fail('El ' . $attribute . ' no es válido.');
                    }

                    // Verifica si el RUT contiene un guion
                    if (strpos($value, '-') === false) {
                        return $fail('El ' . $attribute . ' debe contener un guion antes del dígito verificador.');
                    }
                } catch (\Exception $e) {
                    return $fail('El ' . $attribute . ' no es válido.');
                }
            }],
            'password' => array_merge($this->passwordRules(), ['min:12']),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'rut' => $input['rut'],
            'password' => Hash::make($input['password']),
        ]);

        $user->assignRole(4); // Asignar el rol con ID 4

        return $user;
    }
}
