@section('title', 'Sharat - Create role')

<x-portal-layout>
    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-700">
        <form action="{{ route('portal.roles.store') }}" method="POST">
            @csrf   

            <x-validation-errors class="mb-4" />

            <div class="mb-4">
                <x-label class="mb-2">
                    Nombre del rol
                </x-label>
                <x-input name="name" type="text" class="w-full" placeholder="Ingrese el nombre del rol" value="{{ old('name') }}" />
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fa-solid fa-user-tag mr-1"></i>
                    Crear rol
                </button>
            </div>
        </form>
    </div>
</x-portal-layout>