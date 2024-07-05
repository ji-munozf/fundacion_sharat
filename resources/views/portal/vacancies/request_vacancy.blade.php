@section('title', 'Sharat - Request vacancy')

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
        'name' => 'Vacantes publicados',
        'url' => route('portal.vacancies.vacancies_posted'),
    ],
    [
        'name' => 'Solicitar vacante',
    ],
]">
    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        <div class="mb-4 text-center text-lg dark:text-white">
            Solicitar vacante: {{ $vacancy->name }}
        </div>

        <div class="mb-4 text-start text-sm">
            Descripión: <br> {{ $vacancy->description }}.
        </div>

        <form action="{{ route('portal.vacancies.sendRequestVacancy', $vacancy) }}" method="POST">
            @csrf

            <x-validation-errors class="mb-4" />

            <div class="mb-4">
                <div class="grid items-end gap-6 md:grid-cols-2">
                    <div class="relative">
                        <x-label class="mb-2">Nombres</x-label>
                        <x-input name="names" type="text" class="w-full" placeholder="Ingrese sus nombres"
                            value="{{ old('names') }}" />
                    </div>
                    <div class="relative">
                        <x-label class="mb-2">Apellidos</x-label>
                        <x-input name="last_name" type="text" class="w-full" placeholder="Ingrese sus apellidos"
                            value="{{ old('last_name') }}" />
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Correo electrónico</x-label>
                <x-input name="email" type="text" class="w-full" placeholder="Ingrese su correo electrónico"
                    value="{{ old('email') }}" />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Número de contacto</x-label>
                <x-input name="email" type="text" class="w-full" placeholder="Ingrese su número de contacto"
                    value="{{ old('email') }}" />
            </div>

            <div class="mb-4">
                <x-label class="mb-2" for="file_input">Currículum Vitae</x-label>
                <input
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    id="file_input" type="file" accept=".pdf,.doc,.docx">
                <span class="text-xs text-gray-600 dark:text-gray-400">Solo se aceptan documentos PDF o Word.</span>
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Fortalezas</x-label>
                <x-input name="email" type="text" class="w-full" placeholder="Ingrese sus fortalezas"
                    value="{{ old('email') }}" />
                <span class="text-xs text-gray-600 dark:text-gray-400">Recuerde separar con comas.</span>
            </div>

            <div class="mb-4">
                <x-label class="mb-2">¿Por qué quieres aplicar al puesto?</x-label>
                <textarea id="message" rows="4"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Ingrese el porque..."></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fa-solid fa-paper-plane mr-2"></i>
                    Enviar solicitud
                </button>
            </div>
        </form>
    </div>
</x-portal-layout>
