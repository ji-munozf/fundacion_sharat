@section('title', 'Sharat - Create user')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard'),
    ],
    [
        'name' => 'Usuarios',
        'url' => route('portal.users.index'),
    ],
    [
        'name' => 'Nuevo',
    ],
]">
    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        <form action="{{ route('portal.users.store') }}" method="POST"
            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            @csrf

            <x-validation-errors class="mb-4" />

            <div class="mb-4">
                <x-label class="mb-2">
                    RUT del usuario
                </x-label>

                <x-input name="rut" type="text" class="w-full mb-1" placeholder="Ingrese el RUT del usuario"
                    value="{{ old('rut') }}" />
                <span class="text-xs ml-1 text-gray-600 dark:text-gray-400">
                    Recuerde agregar el guión antes del digito verificador. Ejemplo: xxxxxxx-x
                </span>
            </div>

            <div class="mb-4">
                <x-label class="mb-2">
                    Nombre del usuario
                </x-label>

                <x-input name="name" type="text" class="w-full" placeholder="Ingrese el nombre del usuario"
                    value="{{ old('name') }}" />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">
                    Email del usuario
                </x-label>

                <x-input name='email' type="email" class="w-full" placeholder="Ingrese el email del usuario"
                    value="{{ old('email') }}" />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">
                    Contraseña
                </x-label>

                <x-input name='password' type="password" class="w-full"
                    placeholder="Ingrese la contraseña del usuario" />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">
                    Confirmar contraseña
                </x-label>

                <x-input name='confirm_password' type="password" class="w-full"
                    placeholder="Ingrese nuevamente la contraseña del usuario" />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Rol del usuario</x-label>
                <select name="role"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Seleccione un rol</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Institución del usuario</x-label>
                <select name="institution_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Seleccione una institución</option>
                    @foreach ($institutions as $institution)
                        <option value="{{ $institution->id }}"
                            {{ old('institution_id') == $institution->id ? 'selected' : '' }}>
                            {{ $institution->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fa-solid fa-user-plus mr-1"></i>
                    Crear ususario
                </button>
            </div>
        </form>
    </div>
</x-portal-layout>
