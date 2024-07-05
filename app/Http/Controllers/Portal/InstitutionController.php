<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;

use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class InstitutionController extends Controller implements \Illuminate\Routing\Controllers\HasMiddleware

{
    public static function middleware(): array
    {
        return [
            (new Middleware(middleware: 'can:Visualizar instituciones'))->only('index'),
            (new Middleware(middleware: 'can:Crear instituciones'))->only('create'),
            (new Middleware(middleware: 'can:Actualizar instituciones'))->only('edit'),
            (new Middleware(middleware: 'can:Eliminar instituciones'))->only('destroy'),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $institutions = Institution::all();
        return view('portal.institutions.index', compact('institutions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('portal.institutions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:institutions,name'],
        ]);

        $institution = Institution::create([
            'name' => $request->input('name'),
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El usuario ' . $institution->name . ' se creó correctamente',
        ]);

        return redirect()->route('portal.institutions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Institution $institution)
    {
        return view('portal.institutions.show', compact('institution'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Institution $institution)
    {
        return view('portal.institutions.edit', compact('institution'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Institution $institution)
    {
        $request->validate([
            'name' => ['required', 'unique:institutions,name,' . $institution->id],
        ]);

        $institution->update($request->all());

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El usuario ' . $institution->name . ' se actualizó correctamente.',
        ]);

        return redirect()->route('portal.institutions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Institution $institution)
    {
        $institution->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El usuario ' . $institution->name . '  se eliminó correctamente',
        ]);

        return redirect()->route('portal.institutions.index');
    }
}
