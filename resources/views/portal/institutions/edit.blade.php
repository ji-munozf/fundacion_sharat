@section('title', 'Sharat - Edit institution')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard')
    ],
    [
        'name' => 'Instituciones',
        'url' => route('portal.institutions.index')
    ],
    [
        'name' => 'Editar'
    ],
]">
    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        <form action="{{ route('portal.institutions.update', $institution) }}" method="POST"
            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

            @csrf
            @method('PUT')

            <x-validation-errors class="mb-4" />

            <div class="mb-4">
                <x-label class="mb-2">
                    Nombre de la institución
                </x-label>

                <x-input type="text" id="name" name="name" class="w-full"
                    value="{{ old('name', $institution->name) }}" />
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fa-solid fa-building-columns mr-1"></i>
                    Actualizar institución
                </button>
            </div>
        </form>
    </div>

</x-portal-layout>
