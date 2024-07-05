<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Malahierba\ChileRut\ChileRut;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements \Illuminate\Routing\Controllers\HasMiddleware

{
    public static function middleware(): array
    {
        return [
            (new Middleware(middleware: 'can:Visualizar usuarios'))->only('index'),
            (new Middleware(middleware: 'can:Visualizar usuarios admins'))->only('indexAdmins'),
            (new Middleware(middleware: 'can:Visualizar usuarios instituciones'))->only('indexInstitutions'),
            (new Middleware(middleware: 'can:Visualizar usuarios postulantes'))->only('indexPostulations'),
            (new Middleware(middleware: 'can:Crear usuarios'))->only('create'),
            (new Middleware(middleware: 'can:Actualizar usuarios'))->only('edit'),
            (new Middleware(middleware: 'can:Eliminar usuarios'))->only('destroy'),
            (new Middleware(middleware: 'can:Cambiar contraseña'))->only('password'),
            (new Middleware(middleware: 'can:Hacer premium'))->only('makePremiumView'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUser = auth()->user();

        // Muestra solo los usuarios de la misma institución
        $users = User::where('institution_id', $currentUser->institution_id)
            ->with('institution')
            ->paginate();

        return view('portal.users.index', compact('users'));
    }

    public function indexAdmins()
    {
        // Obtener solo los usuarios con el rol "Admin"
        $users = User::role('Admin')->paginate();

        return view('portal.users.role.admin', compact('users'));
    }

    public function indexInstitutions()
    {
        // Obtener solo los usuarios con el rol "Institución"
        $users = User::role('Institución')->paginate();

        return view('portal.users.role.institution', compact('users'));
    }

    public function indexPostulations()
    {
        // Obtener solo los usuarios con el rol "Postulante"
        $users = User::role('Postulante')->paginate();

        return view('portal.users.role.postulation', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtén todos los roles excepto "Super admin" y "Postulante"
        $roles = Role::whereNotIn('name', ['Super admin', 'Postulante'])->get();
        $institutions = Institution::all();
        return view('portal.users.create', compact('roles', 'institutions'));
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
            'role' => 'required|string|exists:roles,name',
            'institution_id' => 'required|integer|exists:institutions,id',
            'rut' => ['required', 'string', function ($attribute, $value, $fail) {
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
        ]);

        $rut = $request->input('rut');

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'rut' => $rut,
            'institution_id' => $request->input('institution_id'),
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
        // Verifica si el usuario que se intenta editar tiene el rol "Super admin"
        if ($user->hasRole('Super admin')) {
            // Verifica si el usuario autenticado tiene el rol "Admin" o "Super admin"
            if (auth()->user()->hasRole(['Admin', 'Super admin'])) {
                // Si es así, devuelve un error 403
                abort(403);
            }
        }

        $roles = Role::all();
        $institutions = Institution::all();

        // Obtener el rol del usuario
        $role = $user->roles->first()->name;

        // Generar el breadcrumb según el rol
        $breadcrumb_edit = $this->generateBreadcrumbEdit($role);

        // Verificar si el usuario autenticado está editando su propio perfil
        $isEditingOwnProfile = auth()->user()->id === $user->id;

        return view('portal.users.edit', compact('user', 'roles', 'institutions', 'breadcrumb_edit', 'isEditingOwnProfile'));
    }

    private function generateBreadcrumbEdit($role)
    {
        switch ($role) {
            case 'Admin':
                return [
                    [
                        'name' => 'Home',
                        'url' => route('portal.dashboard'),
                    ],
                    [
                        'name' => 'Usuarios',
                        'url' => route('portal.users.index'),
                    ],
                    [
                        'name' => 'Administradores',
                        'url' => route('portal.users.role.admin'),
                    ],
                    [
                        'name' => 'Editar',
                    ],
                ];
            case 'Institución':
                return [
                    [
                        'name' => 'Home',
                        'url' => route('portal.dashboard'),
                    ],
                    [
                        'name' => 'Usuarios',
                        'url' => route('portal.users.index'),
                    ],
                    [
                        'name' => 'Instituciones',
                        'url' => route('portal.users.role.institution'),
                    ],
                    [
                        'name' => 'Editar',
                    ],
                ];
            default:
                return [
                    [
                        'name' => 'Home',
                        'url' => route('portal.dashboard'),
                    ],
                    [
                        'name' => 'Usuarios',
                        'url' => route('portal.users.index'),
                    ],
                    [
                        'name' => 'Editar',
                    ],
                ];
        }
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
            'institution_id' => 'required|integer|exists:institutions,id',
            'rut' => ['required', 'string', function ($attribute, $value, $fail) {
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
        ]);

        $user->update($request->all());
        $user->syncRoles($request->input('role'));

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El usuario ' . $user->name . ' se actualizó correctamente.',
        ]);

        // Obtener el rol del usuario actualizado
        $role = $user->roles->first()->name ?? '';

        // Redirigir según el rol
        switch ($role) {
            case 'Admin':
                return redirect()->route('portal.users.role.admin');
            case 'Institución':
                return redirect()->route('portal.users.role.institution');
            case 'Postulante':
                return redirect()->route('portal.users.role.postulation');
            default:
                return redirect()->route('portal.users.index');
        }
    }

    /**
     * Cambiar contraseña del user.
     */
    public function password(User $user)
    {
        // Obtener el rol del usuario
        $role = $user->roles->first()->name ?? '';

        // Generar el breadcrumb según el rol
        $breadcrumb_pass = $this->generateBreadcrumbPassword($role);

        return view('portal.users.password', compact('user', 'breadcrumb_pass'));
    }

    private function generateBreadcrumbPassword($role)
    {
        switch ($role) {
            case 'Admin':
                return [
                    ['name' => 'Home', 'url' => route('portal.dashboard')],
                    ['name' => 'Usuarios', 'url' => route('portal.users.index')],
                    ['name' => 'Administradores', 'url' => route('portal.users.role.admin')],
                    ['name' => 'Cambiar contraseña'],
                ];
            case 'Institución':
                return [
                    ['name' => 'Home', 'url' => route('portal.dashboard')],
                    ['name' => 'Usuarios', 'url' => route('portal.users.index')],
                    ['name' => 'Instituciones', 'url' => route('portal.users.role.institution')],
                    ['name' => 'Cambiar contraseña'],
                ];
            case 'Postulante':
                return [
                    ['name' => 'Home', 'url' => route('portal.dashboard')],
                    ['name' => 'Usuarios', 'url' => route('portal.users.index')],
                    ['name' => 'Postulantes', 'url' => route('portal.users.role.postulation')],
                    ['name' => 'Cambiar contraseña'],
                ];
            default:
                return [
                    ['name' => 'Home', 'url' => route('portal.dashboard')],
                    ['name' => 'Usuarios', 'url' => route('portal.users.index')],
                    ['name' => 'Cambiar contraseña'],
                ];
        }
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
     * Hacer premium al usuario con role postulante.
     */
    public function makePremiumView(User $user)
    {
        return view('portal.users.makePremiumView', compact('user'));
    }

    public function makePremium(User $user, Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'duration' => 'required|in:1,2', // Mensual o Anual
            'price' => 'required|numeric',
            'file_input' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Establecer la zona horaria de Carbon a "America/Santiago"
        Carbon::setLocale('es');
        $timeZone = 'America/Santiago';
        $startDate = Carbon::now($timeZone);
        $endDate = $request->input('duration') == '1' ? $startDate->copy()->addMonth() : $startDate->copy()->addYear();

        // Manejar el archivo subido
        if ($request->hasFile('file_input')) {
            $file = $request->file('file_input');
            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = $originalFileName;
            $destinationPath = 'comprobantes';
            $counter = 1;

            // Verificar si el archivo existe y modificar el nombre
            while (Storage::disk('public')->exists("$destinationPath/$fileName.$extension")) {
                $fileName = $originalFileName . "($counter)";
                $counter++;
            }

            // Guardar el archivo en la carpeta destino
            $filePath = $file->storeAs($destinationPath, "$fileName.$extension", 'public');
        }

        // Crear una nueva suscripción
        DB::table('subscriptions')->insert([
            'user_id' => $user->id,
            'duration' => $request->input('duration') == '1' ? 'Mensual' : 'Anual',
            'price' => $request->input('price'),
            'bank_transfer_snapshot' => $filePath, // Guardar la ruta del archivo
            'start_date' => $startDate,
            'end_date' => $endDate,
            'created_at' => Carbon::now($timeZone),
            'updated_at' => Carbon::now($timeZone),
        ]);

        // Actualizar el plan del usuario a Premium (id = 2)
        $user->update(['plan_id' => 2]);

        // Redirigir con un mensaje de éxito
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El usuario ' . $user->name . ' se actualizó a premium exitosamente.',
        ]);

        return redirect()->route('portal.users.role.postulation');
    }

    public function cancelSubscription(User $user)
    {
        // Obtener la suscripción del usuario
        $subscription = DB::table('subscriptions')
            ->where('user_id', $user->id)
            ->first();

        if ($subscription) {
            // Eliminar la imagen del comprobante de transferencia bancaria
            if (Storage::disk('public')->exists($subscription->bank_transfer_snapshot)) {
                Storage::disk('public')->delete($subscription->bank_transfer_snapshot);
            }

            // Eliminar la suscripción de la tabla subscriptions
            DB::table('subscriptions')
                ->where('user_id', $user->id)
                ->delete();

            // Actualizar el plan del usuario a Básico (id = 1)
            $user->update(['plan_id' => 1]);

            // Redirigir con un mensaje de éxito
            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Suscripción cancelada!',
                'text' => 'La suscripción del usuario ' . $user->name . ' ha sido cancelada exitosamente.',
            ]);
        } else {
            // Redirigir con un mensaje de error si no se encontró suscripción
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'No se encontró ninguna suscripción activa para el usuario ' . $user->name . '.',
            ]);
        }

        return redirect()->route('portal.users.role.postulation');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Eliminar todas las postulations asociadas
            $user->postulations()->delete();

            // Ahora eliminar el usuario
            $user->delete();

            // Alerta de éxito
            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'El usuario ' . $user->name . ' se eliminó correctamente',
            ]);
        } catch (\Exception $e) {
            // Alerta de error
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'Hubo un problema al eliminar el usuario: ' . $e->getMessage(),
            ]);
        }

        return redirect()->route('portal.users.index');
    }

}
