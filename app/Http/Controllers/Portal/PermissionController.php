<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller implements \Illuminate\Routing\Controllers\HasMiddleware
{
    public static function middleware(): array
    {
        return [
            (new Middleware(middleware: 'can:Visualizar permisos'))->only('index'),
            (new Middleware(middleware: 'can:Crear permisos'))->only('create'),
            (new Middleware(middleware: 'can:Actualizar permisos'))->only('edit'),
            (new Middleware(middleware: 'can:Eliminar permisos'))->only('destroy'),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::paginate(10);
        return view('portal.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('portal.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:permissions,name'],
        ]);

        $permission = Permission::create([
            'name' => $request->input('name'),
            'guard_name' => 'web',
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El permiso ' . $permission->name . ' se creó correctamente.',
        ]);

        return redirect()->route('portal.permissions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        return view('portal.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('portal.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:permissions,name,' . $permission->id],
        ]);

        $permission->update([
            'name' => $request->name
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El permiso ' . $permission->name . ' se actualizó correctamente.',
        ]);

        return redirect()->route('portal.permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permission = DB::table('permissions')->where('id', $id)->first();

        if ($permission) {

            DB::table("permissions")->where('id', $id)->delete();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'El permiso ' . $permission->name . ' se eliminó correctamente',
            ]);
        } else {

            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'El permiso con ID ' . $id . ' no se encontró.',
            ]);
        }

        return redirect()->route('portal.permissions.index');
    }
}
