<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    protected $isValid = true;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $value,
        ])->object();

        if (!$response->success || $response->score < 0.7) {
            $fail('La verificación de ReCaptcha ha fallado.');
            $this->isValid = false;
        }
    }

    /**
     * Check if the recaptcha validation is valid
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes(string $attribute, mixed $value): bool
    {
        $this->validate($attribute, $value, function ($message) {
        });

        return $this->isValid;
    }
}
