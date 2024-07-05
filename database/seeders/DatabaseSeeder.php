<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'Acceso al dashboard']);

        Permission::create(['name' => 'Visualizar perfil']);

        Permission::create(['name' => 'Acceder lista usuarios']);

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

        Permission::create(['name' => 'Visualizar instituciones']);
        Permission::create(['name' => 'Crear instituciones']);
        Permission::create(['name' => 'Actualizar instituciones']);
        Permission::create(['name' => 'Eliminar instituciones']);

        Permission::create(['name' => 'Visualizar vacantes']);
        Permission::create(['name' => 'Crear vacantes']);
        Permission::create(['name' => 'Actualizar vacantes']);
        Permission::create(['name' => 'Eliminar vacantes']);
        Permission::create(['name' => 'Visualizar vacantes publicadas']);
        Permission::create(['name' => 'Solicitar vacante']);

        // Crear Roles
        $superAdminRole = Role::create(['name' => 'Super admin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $institucionRole = Role::create(['name' => 'Institución']);

        // Asignar permisos al rol de admin
        $adminRole->givePermissionTo(['Acceso al dashboard',
            'Visualizar usuarios', 'Crear usuarios', 'Actualizar usuarios', 'Eliminar usuarios', 'Cambiar contraseña',
            'Visualizar roles', 'Crear roles', 'Actualizar roles', 'Eliminar roles', 'Añadir permisos',
            'Visualizar permisos', 'Crear permisos', 'Actualizar permisos', 'Eliminar permisos',
            'Visualizar instituciones', 'Crear instituciones', 'Actualizar instituciones', 'Eliminar instituciones',
            'Visualizar vacantes', 'Crear vacantes', 'Actualizar vacantes', 'Eliminar vacantes', 'Visualizar vacantes publicadas', 'Solicitar vacante',
        ]);

        // Asignar permisos al rol de institución
        $institucionRole->givePermissionTo([
            'Acceso al dashboard', 'Visualizar usuarios',
            'Visualizar vacantes', 'Crear vacantes', 'Actualizar vacantes', 'Eliminar vacantes', 'Visualizar vacantes publicadas', 'Solicitar vacante',
        ]);

        // Crear Institutions
        Institution::create(['name' => 'Sharat']);
        Institution::create(['name' => 'Institución 1']);
        Institution::create(['name' => 'Institución 2']);

        // Crear usuarios y asignarles el role 

        $superAdminUser = User::firstOrCreate([
            'email' => 'superadmin@sharat.cl',
        ], [
            'name' => 'Super Admin Sharat',
            'email' => 'superadmin@sharat.cl',
            'password' => Hash::make('Tsvsoporte2024@'),
            'institution_id' => 1,
        ]);

        $superAdminUser->assignRole($superAdminRole);

        $adminUser = User::firstOrCreate([
            'email' => 'ji.munozf.1999@gmail.com',
        ], [
            'name' => 'Juan Ignacio',
            'email' => 'ji.munozf.1999@gmail.com',
            'password' => Hash::make('JuanIgnacio1'),
            'institution_id' => 1,
        ]);

        $adminUser->assignRole($adminRole);

        $institucionUser1 = User::firstOrCreate([
            'email' => 'test@test.cl',
        ], [
            'name' => 'Test',
            'email' => 'test@test.cl',
            'password' => Hash::make('12345678'),
            'institution_id' => 2,
        ]);

        $institucionUser1->assignRole($institucionRole);

        $institucionUser2 = User::firstOrCreate([
            'email' => 'test2@test.cl',
        ], [
            'name' => 'Test2',
            'email' => 'test2@test.cl',
            'password' => Hash::make('12345678'),
            'institution_id' => 2,
        ]);

        $institucionUser2->assignRole($institucionRole);

        $institucionUser3 = User::firstOrCreate([
            'email' => 'test3@test.cl',
        ], [
            'name' => 'Test3',
            'email' => 'test3@test.cl',
            'password' => Hash::make('12345678'),
            'institution_id' => 3,
        ]);

        $institucionUser3->assignRole($institucionRole);

        $institucionUser4 = User::firstOrCreate([
            'email' => 'test4@test.cl',
        ], [
            'name' => 'Test4',
            'email' => 'test4@test.cl',
            'password' => Hash::make('12345678'),
            'institution_id' => 3,
        ]);

        $institucionUser4->assignRole($institucionRole);

        // Crear Vacancies

        Vacancy::create([
            'name' => 'Profesor de historia',
            'job_title' => 'Profesor',
            'description' => 'Se busca profesor de historia y geografía',
            'contracting_manager' => 'Reclutador colegio X',
            'number_of_vacancies' => 2,
            'active' => 1,
            'user_id' => 3,
            'institution_id' => 2,
        ]);

        Vacancy::create([
            'name' => 'Profesor de matemáticas',
            'job_title' => 'Profesor',
            'description' => 'Se busca matemáticas y debe ser cristiano',
            'contracting_manager' => 'Reclutador colegio Y',
            'number_of_vacancies' => 1,
            'active' => 1,
            'user_id' => 4,
            'institution_id' => 2,
        ]);

        Vacancy::create([
            'name' => 'Profesor de lenguaje',
            'job_title' => 'Profesor',
            'description' => 'Se busca profesor de lenguaje',
            'contracting_manager' => 'Reclutador colegio A',
            'number_of_vacancies' => 2,
            'active' => 1,
            'user_id' => 5,
            'institution_id' => 3,
        ]);

        Vacancy::create([
            'name' => 'Profesor de matemáticas',
            'job_title' => 'Profesor',
            'description' => 'Se busca matemáticas',
            'contracting_manager' => 'Reclutador colegio B',
            'number_of_vacancies' => 1,
            'active' => 1,
            'user_id' => 6,
            'institution_id' => 3,
        ]);
    }
}
