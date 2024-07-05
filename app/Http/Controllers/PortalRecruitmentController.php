<?php

namespace App\Http\Controllers;

use App\Mail\ContactMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
            'mensaje' => 'required',
        ], [
            'contact_number.regex' => 'El número de contacto debe comenzar con +569 seguido de ocho dígitos.',
            'contact_number.phone' => 'El número de contacto no es un número de celular chileno válido.',
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
