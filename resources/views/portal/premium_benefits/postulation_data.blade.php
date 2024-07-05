@section('title', 'Sharat - Datos de postulación')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard'),
    ],
    [
        'name' => 'Datos de postulación',
    ],
]">
    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        <div class="mb-4 text-center text-xl">
            <h1>Datos de postulación</h1>
        </div>
        @if ($postulationData)
            <div class="mb-4">
                <div class="grid items-end gap-6 md:grid-cols-2">
                    <div class="relative">
                        <x-label class="mb-2">Nombres</x-label>
                        <x-input name="names" type="text" class="w-full bg-gray-50 cursor-default"
                            value="{{ $postulationData->names }}" readonly />
                    </div>
                    <div class="relative">
                        <x-label class="mb-2">Apellidos</x-label>
                        <x-input name="last_names" type="text" class="w-full bg-gray-50 cursor-default"
                            value="{{ $postulationData->last_names }}" readonly />
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Correo electrónico</x-label>
                <x-input name="email" type="email" class="w-full bg-gray-50 cursor-default"
                    value="{{ $postulationData->email }}" readonly />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Número de contacto</x-label>
                <x-input name="contact_number" type="text" class="w-full bg-gray-50 cursor-default"
                    value="{{ $postulationData->contact_number }}" readonly />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Currículum Vitae</x-label>
                <x-input name="curriculum_vitae" type="text" class="w-full bg-gray-50 cursor-default"
                    value="{{ basename($postulationData->curriculum_vitae) }}" readonly />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Fortalezas</x-label>
                <x-input name="fortalezas" type="text" class="w-full bg-gray-50 cursor-default"
                    value="{{ $postulationData->strengths }}" readonly />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">¿Por qué quieres aplicar al puesto?</x-label>
                <textarea id="message" name="reasons" rows="4"
                    class="block p-2.5 w-full text-gray-900 bg-gray-50 cursor-default rounded-lg border border-gray-700 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    readonly>{{ $postulationData->reasons }}</textarea>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('portal.premium_benefits.edit_postulation_data', $postulationData) }}">
                    <button type="button"
                        class="px-3 py-2 mr-1 text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        <i class="fa-regular fa-pen-to-square mr-1"></i>
                        Editar datos
                    </button>
                </a>
            </div>
        @else
            <form action="{{ route('portal.premium_benefits.savePostulationData') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <x-validation-errors class="mb-4" />

                <div class="mb-4">
                    <div class="grid items-end gap-6 md:grid-cols-2">
                        <div class="relative">
                            <x-label class="mb-2">Nombres</x-label>
                            <x-input name="names" type="text" class="w-full bg-gray-50"
                                value="{{ old('names') }}" />
                        </div>
                        <div class="relative">
                            <x-label class="mb-2">Apellidos</x-label>
                            <x-input name="last_names" type="text" class="w-full bg-gray-50"
                                value="{{ old('last_names') }}" />
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <x-label class="mb-2">Correo electrónico</x-label>
                    <x-input name="email" type="email" class="w-full bg-gray-50" value="{{ old('email') }}" />
                </div>

                <div class="mb-4">
                    <x-label class="mb-2">Número de contacto</x-label>
                    <x-input name="contact_number" type="text" class="w-full bg-gray-50" placeholder="+569XXXXXXXX"
                        value="{{ old('contact_number') }}"
                        oninput="this.value = this.value.replace(/[^0-9+]/g, '');" />
                    <span class="text-xs text-gray-600 dark:text-gray-400">
                        Recuerde que debe cumplir con el formato indicado.
                    </span>
                </div>

                <div class="mb-4">
                    <x-label class="mb-2" for="file_input">Currículum Vitae</x-label>
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400"
                        id="file_input" name="curriculum_vitae" type="file" accept=".pdf,.doc,.docx">
                    <span class="text-xs text-gray-600 dark:text-gray-400">Solo se aceptan documentos PDF o Word.</span>
                </div>

                <div class="mb-4">
                    <x-label class="mb-2">Fortalezas</x-label>
                    <x-input id="fortalezas" name="fortalezas" type="text" class="w-full bg-gray-50"
                        value="{{ old('fortalezas') }}" placeholder="fortaleza1, fortaleza2, fortaleza3" />
                    <span class="text-xs text-gray-600 dark:text-gray-400">Recuerde que son máximo 10 fortalezas y que
                        debe separar con comas.</span>
                </div>

                <div class="mb-4">
                    <x-label class="mb-2">¿Por qué quieres aplicar al puesto?</x-label>
                    <textarea id="message" name="reasons" rows="4"
                        class="block p-2.5 w-full text-gray-900 bg-gray-50 rounded-lg border focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('reasons') }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <i class="fa-solid fa-paper-plane mr-2"></i>
                        Guardar datos
                    </button>
                </div>
            </form>
        @endif
    </div>

    @push('js')
        <script>
            function confirmDelete() {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "No podrás revertir esta acción",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'green',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-postulation-form').submit();
                    }
                })
            }
        </script>
    @endpush
</x-portal-layout>
