@section('title', 'Sharat - Editar postulación')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard'),
    ],
    [
        'name' => 'Postulaciones',
        'url' => route('portal.applications.index'),
    ],
    [
        'name' => 'Editar'
    ],
]">
    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        <div class="mb-4 text-center text-lg dark:text-white">
            Postular a vacante: {{ $application->vacancy->name }}
        </div>

        <div class="mb-4 text-start text-sm">
            Descripción: <br> {{ $application->vacancy->description }}.
        </div>

        <form action="{{ route('portal.applications.update', $application) }}" method="POST" enctype="multipart/form-data"
            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

            @csrf
            @method('PUT')

            <x-validation-errors class="mb-4" />


            <div class="mb-4">
                <div class="grid items-end gap-6 md:grid-cols-2">
                    <div class="relative">
                        <x-label class="mb-2">Nombres</x-label>
                        <x-input name="names" type="text" class="w-full bg-gray-50"
                            value="{{ old('names', $application->names) }}" />
                    </div>
                    <div class="relative">
                        <x-label class="mb-2">Apellidos</x-label>
                        <x-input name="last_names" type="text" class="w-full bg-gray-50"
                            value="{{ old('last_names', $application->last_names) }}" />
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Correo electrónico</x-label>
                <x-input name="email" type="email" class="w-full bg-gray-50"
                    value="{{ old('email', $application->email) }}" />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Número de contacto</x-label>
                <x-input name="contact_number" type="text" class="w-full bg-gray-50" placeholder="+569XXXXXXXX"
                    value="{{ old('contact_number', $application->contact_number) }}"
                    oninput="this.value = this.value.replace(/[^0-9+]/g, '');" />
                <span class="text-xs text-gray-600 dark:text-gray-400">
                    Recuerde que debe cumplir con el formato indicado.
                </span>
            </div>

            <div class="mb-4">
                <x-label class="mb-2" for="file_input">Currículum Vitae</x-label>
                <input
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    id="file_input" name="curriculum_vitae" type="file" accept=".pdf,.doc,.docx">
                @if ($application->curriculum_vitae)
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">Archivo actual:
                        {{ $application->curriculum_vitae }}</p>
                @endif
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Fortalezas</x-label>
                <x-input id="fortalezas" name="fortalezas" type="text" class="w-full bg-gray-50"
                    value="{{ old('fortalezas', $application->strengths) }}"
                    placeholder="fortaleza 1, fortaleza 2, fortaleza 3" />
                <span class="text-xs text-gray-600 dark:text-gray-400">Recuerde separar con comas.</span>
            </div>

            <div class="mb-4">
                <x-label class="mb-2">¿Por qué quieres aplicar al puesto?</x-label>
                <textarea id="message" name="reasons" rows="4"
                    class="block p-2.5 w-full text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('reasons', $application->reasons) }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fa-solid fa-paper-plane mr-2"></i>
                    Actualizar postulación
                </button>
            </div>
        </form>
    </div>
</x-portal-layout>
