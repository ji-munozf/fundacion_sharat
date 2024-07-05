@section('title', 'Sharat - Editar razones')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard'),
    ],
    [
        'name' => 'Vacantes',
        'url' => route('portal.vacancies.index'),
    ],
    [
        'name' => 'Ver postulantes',
        'url' => route('portal.vacancies.candidates', $vacancy->id), // Aquí pasas el parámetro 'vacancy'
    ],
    [
        'name' => 'Editar razones',
    ],
]">

    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        @php
            $statusText = $postulationStatus->status ? 'Aceptar postulación' : 'Rechazar postulación';
        @endphp
        <h1 class="text-xl text-center text-black dark:text-white">{{ $statusText }}</h1>
        <form method="POST" action="{{ route('postulation.updateReasons', $postulation->id) }}">
            @csrf
            <div class="mb-3">
                <x-label class="mb-2">Razones</x-label>
                <textarea id="reasons" name="reasons" rows="4"
                    class="block p-2.5 w-full text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('reasons', $postulationStatus->reasons) }}</textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Actualizar
                </button>
            </div>
        </form>
    </div>

</x-portal-layout>
