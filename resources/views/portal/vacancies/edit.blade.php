@section('title', 'Sharat - Edit vacanty')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard')
    ],
    [
        'name' => 'Vacantes',
        'url' => route('portal.vacancies.index')
    ],
    [
        'name' => 'Editar'
    ],
]">
    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        <form action="{{ route('portal.vacancies.update', $vacancy) }}" method="POST"
            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

            @csrf
            @method('PUT')

            <x-validation-errors class="mb-4" />

            <div class="mb-4">
                <x-label class="mb-2">Nombre de la vacante</x-label>
                <x-input name="name" type="text" class="w-full" placeholder="Ingrese el nombre de la vacante"
                    value="{{ old('name', $vacancy->name) }}" />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Título del puesto</x-label>
                <x-input name="job_title" type="text" class="w-full" placeholder="Ingrese el título del puesto"
                    value="{{ old('job_title', $vacancy->job_title) }}" />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Descripción</x-label>
                <textarea name="description" rows="4"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Ingrese la descripción del puesto">{{ old('description', $vacancy->description) }}</textarea>
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Gerente de contrataciones</x-label>
                <x-input name="contracting_manager" type="text" class="w-full"
                    placeholder="Ingrese el gerente de contrataciones"
                    value="{{ old('contracting_manager', $vacancy->contracting_manager) }}" />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Número de vacantes</x-label>
                <x-input name="number_of_vacancies" type="number" class="w-full"
                    placeholder="Ingrese la cantidad de vacantes" value="{{ old('number_of_vacancies', $vacancy->number_of_vacancies) }}" />
            </div>

            <label class="inline-flex items-center cursor-pointer">
                <span class="me-3 text-sm font-medium text-gray-900 dark:text-gray-300">Activo</span>
                <input type="checkbox" name="active" value="1" class="sr-only peer" {{ old('active', $vacancy->active) ? 'checked' : '' }}>
                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
            </label>

            <div class="flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fa-solid fa-paste mr-1"></i>
                    Actualizar vacante
                </button>
            </div>
        </form>
    </div>
</x-portal-layout>
