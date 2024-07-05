@section('title', 'Sharat - Add permission to role')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard')
    ],
    [
        'name' => 'Roles',
        'url' => route('portal.roles.index')
    ],
    [
        'name' => 'Añadir permisos'
    ],
]">
    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        <div class="mb-4 text-center text-lg">
            Rol: {{ $role->name }}
        </div>

        <form action="{{ route('portal.roles.addPermissionToRole', $role) }}" method="POST"
            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

            @csrf
            @method('PUT')

            <x-validation-errors class="mb-4" />

            @if ($permissions->count())
                <div class="mb-4">
                    <x-label class="mb-2">
                        Permisos
                    </x-label>
                    <ul>
                        @foreach ($permissions as $permission)
                            <li>
                                <x-label>
                                    <x-checkbox name="permissions[]" value="{{ $permission->name }}" :checked="in_array(
                                        $permission->id,
                                        old('permissions', $role->permissions->pluck('id')->toArray()),
                                    )" />
                                    {{ $permission->name }}
                                </x-label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="p-4 mb-4 text-sm text-left text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-white"
                    role="alert">
                    No se han registrado permisos.
                </div>
            @endif

            <div class="flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fa-solid fa-key mr-1"></i>
                    Agregar permisos
                </button>
            </div>
        </form>
    </div>
</x-portal-layout>
