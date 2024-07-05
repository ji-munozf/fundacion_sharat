<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements \Illuminate\Routing\Controllers\HasMiddleware
{
    public static function middleware(): array
    {
        return [
            (new Middleware(middleware: 'can:Visualizar roles'))->only('index'),
            (new Middleware(middleware: 'can:Crear roles'))->only('create'),
            (new Middleware(middleware: 'can:Actualizar roles'))->only('edit'),
            (new Middleware(middleware: 'can:Eliminar roles'))->only('destroy'),
            (new Middleware(middleware: 'can:Añadir permisos'))->only('addPermissionToRole'),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('portal.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('portal.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:roles,name'],
        ]);

        $role = Role::create([
            'name' => $request->input('name'),
            'guard_name' => 'web',
        ]);
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El rol ' . $role->name . ' se creó correctamente.',
        ]);

        return redirect()->route('portal.roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return view('portal.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        if ((auth()->user()->hasRole('Super admin') && auth()->user()->id == $role->id) || ($role->name == 'Super admin' && auth()->user()->hasRole('Admin'))) {
            abort(403);
        }

        $permissions = Permission::all();
        return view('portal.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'unique:roles,name,' . $role->id],
        ]);

        $role->update([
            'name' => $request->name
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El rol ' . $role->name . ' se actualizó correctamente.',
        ]);

        return redirect()->route('portal.roles.index');
    }

    /**
     * Agregar permisos al role.
     */
    public function addPermissionToRole($id)
    {
        $permissions = Permission::all();
        $role = Role::findOrFail($id);

        if (auth()->user()->hasRole('Admin')) {
            // Si el rol al que se intenta asignar permisos es 'Super admin', aborta la operación
            if ($role->name == 'Super admin') {
                abort(403);
            }
        }

        if (auth()->user()->hasRole('Super admin') && auth()->user()->id == $role->id) {
            abort(403);
        }
        
        return view('portal.roles.add-permissions', compact('role', 'permissions'));
    }

    public function givePermissionToRole(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'required',
        ]);

        $role = Role::findOrFail($id);
        $role->syncPermissions($request->permissions);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El permiso se agregó al rol',
        ]);

        return redirect()->route('portal.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = DB::table('roles')->where('id', $id)->first();

        if ((auth()->user()->hasRole('Super admin') && auth()->user()->id == $role->id) || ($role->name == 'Super admin' && auth()->user()->hasRole('Admin'))) {
            abort(403);
        }

        if ($role) {
            
            DB::table("roles")->where('id', $id)->delete();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'El rol ' . $role->name . ' se eliminó correctamente',
            ]);
        } else {

            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'El rol con ID ' . $id . ' no se encontró.',
            ]);
        }

        return redirect()->route('portal.roles.index');
    }
}
