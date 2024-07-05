<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements \Illuminate\Routing\Controllers\HasMiddleware
{
    public static function middleware(): array
    {
        return [
            (new Middleware(middleware: 'can:Visualizar usuarios'))->only('index'),
            (new Middleware(middleware: 'can:Crear usuarios'))->only('create'),
            (new Middleware(middleware: 'can:Actualizar usuarios'))->only('edit'),
            (new Middleware(middleware: 'can:Eliminar usuarios'))->only('destroy'),
            (new Middleware(middleware: 'can:Cambiar contraseña'))->only('password'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate();
        return view('portal.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('portal.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email',
            'password' => 'required|min:12|max:255',
            'confirm_password' => 'required|same:password',
            'role' => 'required|string|exists:roles,name', // Validar que el rol exista
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        // Asignar el rol al usuario
        $user->assignRole($request->input('role'));

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El usuario ' . $user->name . ' se creó correctamente',
        ]);

        return redirect()->route('portal.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('portal.users.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if ((auth()->user()->hasRole('Super admin') && auth()->user()->id == $user->id) || ($user->name == 'Super admin' && auth()->user()->hasRole('Admin'))) {
            abort(403);
        }

        $roles = Role::all();
        return view('portal.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|max:255|unique:users,email,{$user->id}",
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->update($request->all());
        $user->syncRoles($request->input('role'));

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El usuario ' . $user->name . ' se actualizó correctamente.',
        ]);

        return redirect()->route('portal.users.index');
    }

    /**
     * Cambiar contraseña del user.
     */
    public function password(User $user)
    {
        return view('portal.users.password', compact('user'));
    }

    public function updatePass(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:12|max:255',
            'confirm_password' => 'required|same:password',
        ]);

        if (Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['password' => 'La nueva contraseña no puede ser igual a la contraseña actual.']);
        }

        // Actualiza la contraseña del usuario en la base de datos
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'La contraseña del usuario ' . $user->name . ' se actualizó correctamente.',
        ]);

        return redirect()->route('portal.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El usuario ' . $user->name . '  se eliminó correctamente',
        ]);

        return redirect()->route('portal.users.index');
    }
}
