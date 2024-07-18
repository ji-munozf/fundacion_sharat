@section('title', 'Sharat - Postular a vacante')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard'),
    ],
    [
        'name' => 'Postulaciones',
        'url' => route('portal.postulations.index'),
    ],
    [
        'name' => 'Postular',
    ],
]">

    <head>
        <!-- reCAPTCHA Google -->
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    </head>

    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        <div class="mb-4 text-center text-lg dark:text-white">
            Postular a vacante: {{ $vacancy->name }}
        </div>

        <div class="mb-4 text-start text-sm">
            Descripión: <br> {{ $vacancy->description }}.
        </div>

        <form id="postulation-form" action="{{ route('portal.postulations.sendRequestVacancy', $vacancy) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <x-validation-errors class="mb-4" />

            @if (auth()->user()->plan_id == 2)
                <div class="mb-4">
                    <div class="flex items-center">
                        <input
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-900 dark:border-gray-600"
                            type="checkbox" id="autofill-checkbox" name="autofill" value="1"
                            {{ old('autofill', 0) == 1 ? 'checked' : '' }} />
                        <span class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Autocompletar
                            datos</span>
                    </div>
                </div>
            @endif

            <input type="hidden" id="postulation_data_available"
                value="{{ $postulationDataAvailable ? 'true' : 'false' }}">

            <div class="mb-4">
                <div class="grid items-end gap-6 md:grid-cols-2">
                    <div class="relative">
                        <x-label class="mb-2">Nombres</x-label>
                        <x-input id="names" name="names" type="text" class="w-full bg-gray-50"
                            value="{{ old('names') }}" />
                    </div>
                    <div class="relative">
                        <x-label class="mb-2">Apellidos</x-label>
                        <x-input id="last_names" name="last_names" type="text" class="w-full bg-gray-50"
                            value="{{ old('last_names') }}" />
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Correo electrónico</x-label>
                <x-input id="email" name="email" type="email" class="w-full bg-gray-50"
                    value="{{ old('email') }}" />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Número de contacto</x-label>
                <x-input id="contact_number" name="contact_number" type="text" class="w-full bg-gray-50 mb-1"
                    placeholder="+569XXXXXXXX" value="{{ old('contact_number') }}"
                    oninput="this.value = this.value.replace(/[^0-9+]/g, '');" />
                <span class="text-sm text-gray-600 dark:text-gray-400">Recuerde que debe cumplir con el formato
                    indicado.</span>
            </div>

            <div class="mb-4">
                <x-label class="mb-2" for="file_input">Currículum Vitae</x-label>
                <input
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400"
                    id="file_input" name="curriculum_vitae" type="file" accept=".pdf,.doc,.docx">
                <x-input type="text" id="file_name_display" class="w-full bg-gray-50 mb-1" readonly
                    style="display:none;" value="{{ old('file_name_display') }}" />
                <input type="hidden" name="file_name_display" id="file_name_hidden"
                    value="{{ old('file_name_display') }}">
                <span class="text-sm text-gray-600 dark:text-gray-400">Solo se aceptan documentos PDF o Word.</span>
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Fortalezas</x-label>
                <x-input id="fortalezas" name="fortalezas" type="text" class="w-full bg-gray-50 mb-1"
                    value="{{ old('fortalezas') }}" placeholder="fortaleza1, fortaleza2, fortaleza3" />
                <span class="text-sm text-gray-600 dark:text-gray-400">Recuerde que son máximo 10 fortalezas y que debe
                    separar con comas.</span>
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
                    Enviar solicitud
                </button>
            </div>
        </form>
    </div>

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const autofillCheckbox = document.getElementById('autofill-checkbox');
                const fileInput = document.getElementById('file_input');
                const fileNameDisplay = document.getElementById('file_name_display');
                const fileNameHidden = document.getElementById('file_name_hidden');
                const postulationDataAvailable = document.getElementById('postulation_data_available').value === 'true';

                const inputFields = [
                    'names', 'last_names', 'email', 'contact_number', 'fortalezas', 'message'
                ];

                function setDisabledState(disabled) {
                    inputFields.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (disabled) {
                            field.setAttribute('data-disabled', 'true');
                            field.setAttribute('disabled', 'true');
                            field.classList.add('cursor-not-allowed');
                        } else {
                            field.removeAttribute('data-disabled');
                            field.removeAttribute('disabled');
                            field.classList.remove('cursor-not-allowed');
                        }
                    });

                    if (disabled) {
                        fileInput.style.display = 'none';
                        fileNameDisplay.style.display = 'block';
                        fileNameDisplay.classList.add('cursor-not-allowed');
                    } else {
                        fileInput.style.display = 'block';
                        fileNameDisplay.style.display = 'none';
                        fileNameDisplay.classList.remove('cursor-not-allowed');
                    }
                }

                if (autofillCheckbox.checked) {
                    setDisabledState(true);
                }

                autofillCheckbox.addEventListener('change', async function() {
                    if (autofillCheckbox.checked) {
                        if (!postulationDataAvailable) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Datos de postulación faltantes',
                                text: 'Debe primero agregar sus datos de postulación para usar esta función.',
                                showCancelButton: true,
                                confirmButtonColor: 'green',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Agregar datos',
                                cancelButtonText: 'Cancelar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href =
                                        "{{ route('portal.premium_benefits.postulation_data') }}";
                                } else {
                                    autofillCheckbox.checked =
                                        false; // Desmarca el checkbox si se cancela
                                }
                            });
                            return;
                        }

                        try {
                            const response = await fetch(
                                '{{ route('portal.postulations.userData', ['plan_id' => auth()->user()->plan_id]) }}'
                            );
                            const userData = await response.json();

                            document.getElementById('names').value = userData.names;
                            document.getElementById('last_names').value = userData.last_names;
                            document.getElementById('email').value = userData.email;
                            document.getElementById('contact_number').value = userData.contact_number;
                            document.getElementById('fortalezas').value = userData.strengths;
                            document.getElementById('message').value = userData.reasons;

                            if (userData.curriculum_vitae) {
                                const baseFileName = userData.curriculum_vitae.split('/').pop();
                                fileNameDisplay.value = baseFileName;
                                fileNameHidden.value = baseFileName;
                            } else {
                                fileNameDisplay.value = '';
                                fileNameHidden.value = '';
                            }

                            setDisabledState(true);
                        } catch (error) {
                            console.error('Error fetching user data:', error);
                        }
                    } else {
                        document.getElementById('names').value = '';
                        document.getElementById('last_names').value = '';
                        document.getElementById('email').value = '';
                        document.getElementById('contact_number').value = '';
                        document.getElementById('fortalezas').value = '';
                        document.getElementById('message').value = '';

                        setDisabledState(false);
                    }
                });

                fileInput.addEventListener('change', function() {
                    const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : '';
                    fileNameDisplay.value = fileName;
                    fileNameHidden.value = fileName;
                });

                document.getElementById('postulation-form').addEventListener('submit', function(event) {
                    // Eliminar el atributo 'disabled' temporalmente antes de enviar el formulario
                    inputFields.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (field.getAttribute('data-disabled') === 'true') {
                            field.removeAttribute('disabled');
                        }
                    });
                });
            });

            document.addEventListener('submit', function(e) {
                e.preventDefault();
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {
                        action: 'submit'
                    }).then(function(token) {
                        let form = e.target;

                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'g-recaptcha-response';
                        input.value = token;

                        form.appendChild(input);
                        form.submit();
                    });
                });
            });
        </script>
    @endpush
</x-portal-layout>
