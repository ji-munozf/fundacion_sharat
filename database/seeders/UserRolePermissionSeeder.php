<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'Acceso al dashboard']);

        Permission::create(['name' => 'Visualizar usuarios']);
        Permission::create(['name' => 'Crear usuarios']);
        Permission::create(['name' => 'Actualizar usuarios']);
        Permission::create(['name' => 'Eliminar usuarios']);
        Permission::create(['name' => 'Cambiar contraseña']);

        Permission::create(['name' => 'Visualizar roles']);
        Permission::create(['name' => 'Crear roles']);
        Permission::create(['name' => 'Actualizar roles']);
        Permission::create(['name' => 'Eliminar roles']);
        Permission::create(['name' => 'Añadir permisos']);

        Permission::create(['name' => 'Visualizar permisos']);
        Permission::create(['name' => 'Crear permisos']);
        Permission::create(['name' => 'Actualizar permisos']);
        Permission::create(['name' => 'Eliminar permisos']);

        Permission::create(['name' => 'Visualizar vacantes']);

        // Crear Roles
        $superAdminRole = Role::create(['name' => 'Super admin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $institucionRole = Role::create(['name' => 'Institución']);

        // Let's give few permissions to admin role.
        $adminRole->givePermissionTo(['Acceso al dashboard',
            'Visualizar usuarios', 'Crear usuarios', 'Actualizar usuarios', 'Eliminar usuarios', 'Cambiar contraseña',
            'Visualizar roles', 'Crear roles', 'Actualizar roles', 'Eliminar roles', 'Añadir permisos',
            'Visualizar permisos', 'Crear permisos', 'Actualizar permisos', 'Eliminar permisos',
            'Visualizar vacantes'
        ]);
        $institucionRole->givePermissionTo([
            'Acceso al dashboard', 'Visualizar vacantes'
        ]);

        // Crear usuarios y asignarles el role 

        $superAdminUser = User::firstOrCreate([
            'email' => 'superadmin@sharat.cl',
        ], [
            'name' => 'Super Admin Sharat',
            'email' => 'superadmin@sharat.cl',
            'password' => Hash::make('Tsvsoporte2024@'),
        ]);

        $superAdminUser->assignRole($superAdminRole);

        $adminUser = User::firstOrCreate([
            'email' => 'ji.munozf.1999@gmail.com',
        ], [
            'name' => 'Juan Ignacio',
            'email' => 'ji.munozf.1999@gmail.com',
            'password' => Hash::make('JuanIgnacio1'),
        ]);

        $adminUser->assignRole($adminRole);

        $institucionUser = User::firstOrCreate([
            'email' => 'test@test.cl',
        ], [
            'name' => 'Test',
            'email' => 'test@test.cl',
            'password' => Hash::make('12345678'),
        ]);

        $institucionUser->assignRole($institucionRole);
    }
}
