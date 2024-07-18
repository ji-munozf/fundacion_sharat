<?php

namespace App\Http\Controllers;

use App\Mail\ContactMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use \App\Rules\Recaptcha;

class PortalRecruitmentController extends Controller
{
    public function index()
    {
        return view('portal_recruitment');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'contact_number' => ['required', 'regex:/^\+569[0-9]{8}$/', 'phone:CL,mobile'],
            'institution' => 'required',
            'message' => 'required',
            'g-recaptcha-response' => ['required', new Recaptcha],
        ], [
            'contact_number.required' => 'El campo número de contacto es obligatorio.',
            'contact_number.regex' => 'El número de contacto debe comenzar con +569 seguido de ocho dígitos.',
            'contact_number.phone' => 'El número de contacto no es un número de celular chileno válido.',
            'institution.required' => 'El campo nombre de la institución es obligatorio.',
        ]);

        Mail::to('soporte@fundacionsharat.cl')->send(new ContactMailable($request->all()));

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Sus datos de contacto fueron enviados satisfactoriamente.',
        ]);

        return back();
    }

}
