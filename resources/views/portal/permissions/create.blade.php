@section('title', 'Sharat - Create permission')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard')
    ],
    [
        'name' => 'Permisos',
        'url' => route('portal.permissions.index')
    ],
    [
        'name' => 'Nuevo',
    ],
]">
    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        <form action="{{ route('portal.permissions.store') }}" method="POST">
            @csrf

            <x-validation-errors class="mb-4" />

            <div class="mb-4">
                <x-label class="mb-2">
                    Nombre del permiso
                </x-label>
                <x-input name="name" type="text" class="w-full" placeholder="Ingrese el nombre del permiso" value="{{ old('name') }}" />
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fa-solid fa-key mr-1"></i>
                    Crear permiso
                </button>
            </div>
        </form>
    </div>
</x-portal-layout>