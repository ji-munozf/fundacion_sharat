<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Plan;
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

        Permission::create(['name' => 'Visualizar usuarios']);
        Permission::create(['name' => 'Visualizar usuarios admins']);
        Permission::create(['name' => 'Visualizar usuarios instituciones']);
        Permission::create(['name' => 'Visualizar usuarios postulantes']);
        Permission::create(['name' => 'Crear usuarios']);
        Permission::create(['name' => 'Actualizar usuarios']);
        Permission::create(['name' => 'Eliminar usuarios']);
        Permission::create(['name' => 'Cambiar contraseña']);
        Permission::create(['name' => 'Hacer premium']);

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
        Permission::create(['name' => 'Visualizar postulantes']);
        Permission::create(['name' => 'Descargar CV']);
        Permission::create(['name' => 'Visualizar botón aceptar']);
        Permission::create(['name' => 'Visualizar botón rechazar']);
        Permission::create(['name' => 'Actualizar razones']);
        Permission::create(['name' => 'Cancelar elección']);

        Permission::create(['name' => 'Visualizar postulación']);
        Permission::create(['name' => 'Crear postulación']);
        Permission::create(['name' => 'Editar postulación']);
        Permission::create(['name' => 'Cancelar postulación']);
        Permission::create(['name' => 'Eliminar postulación']);

        Permission::create(['name' => 'Visualizar planes']);

        Permission::create(['name' => 'Acceder a datos de postulación']);
        Permission::create(['name' => 'Guardar datos de postulación']);
        Permission::create(['name' => 'Editar datos de postulación']);
        Permission::create(['name' => 'Eliminar datos de postulación']);

        Permission::create(['name' => 'Visualizar reportes']);
        Permission::create(['name' => 'Visualizar historial suscripciones']);
        Permission::create(['name' => 'Visualizar historial vacantes']);
        Permission::create(['name' => 'Visualizar historial postulaciones']);
        Permission::create(['name' => 'Visualizar historial datos de postulación']);

        Permission::create(['name' => 'Exportar vacantes a excel']);
        Permission::create(['name' => 'Exportar postulaciones a excel']);
        Permission::create(['name' => 'Exportar suscripciones a excel']);
        Permission::create(['name' => 'Exportar datos de postulación a excel']);

        Permission::create(['name' => 'Revertir eliminar vacante']);
        Permission::create(['name' => 'Revertir eliminar postulación']);

        Permission::create(['name' => 'Eliminar los datos de postulación del usuario']);
        Permission::create(['name' => 'Eliminar todas las vacantes']);
        Permission::create(['name' => 'Eliminar todas las postulaciones']);
        Permission::create(['name' => 'Eliminar todas las suscripciones']);
        Permission::create(['name' => 'Eliminar todos los datos de postulación']);

        // Crear Roles
        $superAdminRole = Role::create(['name' => 'Super admin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $institucionRole = Role::create(['name' => 'Institución']);
        $postulanteRole = Role::create(['name' => 'Postulante']);

        // Crear Planes
        Plan::create([
            'name' => 'Gratuito',
            'monthly_price' => 0,
            'yearly_price' => 0,
        ]);

        Plan::create([
            'name' => 'Premium',
            'monthly_price' => 2500,
            'yearly_price' => 30000,
        ]);

        // Asignar permisos al rol de institución
        $adminRole->givePermissionTo([
            'Acceso al dashboard',
            'Visualizar usuarios', 'Visualizar usuarios admins', 'Visualizar usuarios instituciones', 'Visualizar usuarios postulantes',
            'Crear usuarios', 'Actualizar usuarios', 'Eliminar usuarios', 'Cambiar contraseña', 'Hacer premium',
            'Visualizar roles', 'Crear roles', 'Actualizar roles', 'Eliminar roles', 'Añadir permisos',
            'Visualizar permisos', 'Crear permisos', 'Actualizar permisos', 'Eliminar permisos',
            'Visualizar instituciones', 'Crear instituciones', 'Actualizar instituciones', 'Eliminar instituciones',
            'Visualizar vacantes', 'Crear vacantes', 'Actualizar vacantes', 'Eliminar vacantes',
            'Visualizar reportes', 'Visualizar historial suscripciones', 'Visualizar historial vacantes', 'Visualizar historial postulaciones', 'Visualizar historial datos de postulación',
            'Exportar vacantes a excel', 'Exportar postulaciones a excel', 'Exportar suscripciones a excel', 'Exportar datos de postulación a excel',
            'Revertir eliminar vacante', 'Revertir eliminar postulación', 'Eliminar los datos de postulación del usuario',
            'Eliminar todas las vacantes', 'Eliminar todas las postulaciones', 'Eliminar todas las suscripciones', 'Cancelar postulación', 'Eliminar todos los datos de postulación',
        ]);

        // Asignar permisos al rol de institución
        $institucionRole->givePermissionTo([
            'Acceso al dashboard',
            'Visualizar vacantes', 'Crear vacantes', 'Actualizar vacantes', 'Eliminar vacantes',
            'Visualizar postulantes', 'Descargar CV', 'Visualizar botón aceptar', 'Visualizar botón rechazar',
            'Actualizar razones', 'Cancelar elección',
        ]);

        // Asignar permisos al rol de postulante
        $postulanteRole->givePermissionTo([
            'Acceso al dashboard',
            'Visualizar postulación', 'Crear postulación', 'Editar postulación', 'Cancelar postulación',
            'Visualizar planes', 'Acceder a datos de postulación', 'Guardar datos de postulación', 'Editar datos de postulación', 'Eliminar datos de postulación',
        ]);

        // Crear Institutions
        Institution::create(['name' => 'Sharat']);
        Institution::create(['name' => 'Institución 1']);
        Institution::create(['name' => 'Institución 2']);

        // Crear usuarios y asignarles el role
        $superAdminUser = User::firstOrCreate([
            'rut' => '11675997-7',
            'email' => 'soporte@fundacionsharat.cl',
        ], [
            'rut' => '11675997-7',
            'name' => 'Soporte Sharat',
            'email' => 'soporte@fundacionsharat.cl',
            'password' => Hash::make('Tsvsoporte2024@'),
            'institution_id' => 1,
        ]);

        $superAdminUser->assignRole($superAdminRole);

        $adminUser = User::firstOrCreate([
            'rut' => '20287177-1',
            'email' => 'ignacio.munoz@tsv.cl',
        ], [
            'rut' => '20287177-1',
            'name' => 'Juan Ignacio Muñoz',
            'email' => 'ignacio.munoz@tsv.cl',
            'password' => Hash::make('JuanIgnacio1'),
            'institution_id' => 1,
        ]);

        $adminUser->assignRole($adminRole);

        $institucionUser1 = User::firstOrCreate([
            'rut' => '13197256-3',
            'email' => 'test@test.cl',
        ], [
            'rut' => '13197256-3',
            'name' => 'Test',
            'email' => 'test@test.cl',
            'password' => Hash::make('12345678'),
            'institution_id' => 2,
        ]);

        $institucionUser1->assignRole($institucionRole);

        $institucionUser2 = User::firstOrCreate([
            'rut' => '11232425-9',
            'email' => 'test2@test.cl',
        ], [
            'rut' => '11232425-9',
            'name' => 'Test2',
            'email' => 'test2@test.cl',
            'password' => Hash::make('12345678'),
            'institution_id' => 2,
        ]);

        $institucionUser2->assignRole($institucionRole);

        $institucionUser3 = User::firstOrCreate([
            'rut' => '17013603-9',
            'email' => 'test3@test.cl',
        ], [
            'rut' => '17013603-9',
            'name' => 'Test3',
            'email' => 'test3@test.cl',
            'password' => Hash::make('12345678'),
            'institution_id' => 3,
        ]);

        $institucionUser3->assignRole($institucionRole);

        $institucionUser4 = User::firstOrCreate([
            'rut' => '15132109-7',
            'email' => 'test4@test.cl',
        ], [
            'rut' => '15132109-7',
            'name' => 'Test4',
            'email' => 'test4@test.cl',
            'password' => Hash::make('12345678'),
            'institution_id' => 3,
        ]);

        $institucionUser4->assignRole($institucionRole);

        $postulanteUser1 = User::firstOrCreate([
            'rut' => '22815889-5',
            'email' => 'ji.munozf.1999@gmail.com',
        ], [
            'rut' => '22815889-5',
            'name' => 'Juan Ignacio',
            'email' => 'ji.munozf.1999@gmail.com',
            'password' => Hash::make('JuanIgnacio1'),
            'institution_id' => null,
            'plan_id' => 1,
        ]);

        $postulanteUser1->assignRole($postulanteRole);

        $postulanteUser2 = User::firstOrCreate([
            'rut' => '21384504-7',
            'email' => 'eli.cornejo@test.cl',
        ], [
            'rut' => '21384504-7',
            'name' => 'Elisa Cornejo',
            'email' => 'eli.cornejo@test.cl',
            'password' => Hash::make('12345678'),
            'institution_id' => null,
            'plan_id' => 1,
        ]);

        $postulanteUser2->assignRole($postulanteRole);

        // Crear Vacancies
        Vacancy::create([
            'name' => 'Profesor de historia',
            'job_title' => 'Profesor',
            'description' => 'Se busca profesor de historia y geografía',
            'contracting_manager' => 'Reclutador colegio X',
            'number_of_vacancies' => 2,
            'gross_salary' => 777777,
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
            'gross_salary' => 777777,
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
            'gross_salary' => 777777,
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
            'gross_salary' => 777777,
            'active' => 1,
            'user_id' => 6,
            'institution_id' => 3,
        ]);

    }
}
