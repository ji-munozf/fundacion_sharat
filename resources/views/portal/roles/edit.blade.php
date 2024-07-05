@section('title', 'Sharat - Edit role')

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
        'name' => 'Editar'
    ],
]">
    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        <form action="{{ route('portal.roles.update', $role) }}" method="POST"
            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

            @csrf
            @method('PUT')

            <x-validation-errors class="mb-4" />

            <div class="mb-4">
                <x-label class="mb-2">
                    Nombre del rol
                </x-label>

                <x-input type="text" id="name" name="name" class="w-full"
                    value="{{ old('name', $role->name) }}" />
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fa-solid fa-user-tag mr-1"></i>
                    Actualizar rol
                </button>
            </div>
        </form>
    </div>
</x-portal-layout>
