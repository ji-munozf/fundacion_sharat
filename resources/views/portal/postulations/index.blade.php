@section('title', 'Sharat - Vacantes publicadas')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard'),
    ],
    [
        'name' => 'Postulaciones',
    ],
]">
    @if ($vacancies->count())
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-4">
            <table class="w-full text-sm rtl:text-right text-center text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        @if ($isPostulantWithPlan2)
                            <th scope="col" class="px-6 py-3">Seleccionar</th>
                        @endif
                        <th scope="col" class="px-6 py-3">Nombre</th>
                        <th scope="col" class="px-6 py-3">Título del puesto</th>
                        <th scope="col" class="px-6 py-3">Descripción</th>
                        <th scope="col" class="px-6 py-3">Gerente de contrataciones</th>
                        <th scope="col" class="px-6 py-3">Número de vacantes</th>
                        <th scope="col" class="px-6 py-3">Institución</th>
                        <th scope="col" class="px-6 py-3">Estado</th>
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vacancies as $vacancy)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            @if ($isPostulantWithPlan2)
                                <td class="px-6 py-4">
                                    <x-label>
                                        @php
                                            $isDisabled =
                                                isset($postulations[$vacancy->id]) &&
                                                ($postulations[$vacancy->id]->status->status === null ||
                                                    $postulations[$vacancy->id]->status->status === 0 ||
                                                    $postulations[$vacancy->id]->status->status === 1);
                                        @endphp
                                        <x-checkbox :disabled="$isDisabled" id="checkbox-{{ $vacancy->id }}"
                                            class="{{ $isDisabled ? 'opacity-50 cursor-default' : 'cursor-pointer' }}"
                                            onchange="toggleButton({{ $vacancy->id }})" />
                                    </x-label>
                                </td>
                            @endif
                            <td class="px-6 py-4">{{ $vacancy->name }}</td>
                            <td class="px-6 py-4">{{ $vacancy->job_title }}</td>
                            <td class="px-6 py-4">{{ $vacancy->description }}</td>
                            <td class="px-6 py-4">{{ $vacancy->contracting_manager }}</td>
                            <td class="px-6 py-4">
                                @if ($vacancy->number_of_vacancies == 0)
                                    <span class="font-semibold text-red-500">Sin vacantes disponibles</span>
                                @else
                                    {{ $vacancy->number_of_vacancies }}
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $vacancy->user->institution->name }}</td>
                            <td class="px-6 py-4">
                                @if (isset($postulations[$vacancy->id]))
                                    @php
                                        $postulationStatus = $postulations[$vacancy->id]->status->status ?? null;
                                        $statusText = 'Pendiente';
                                        $statusClass = 'font-semibold text-yellow-500'; // Clase por defecto para "Pendiente"

                                        if ($postulationStatus !== null) {
                                            if ($postulationStatus == 1) {
                                                $statusText = 'Aceptado';
                                                $statusClass = 'font-semibold text-green-500'; // Clase para "Aceptado"
                                            } elseif ($postulationStatus == 0) {
                                                $statusText = 'Rechazado';
                                                $statusClass = 'font-semibold text-red-500'; // Clase para "Rechazado"
                                            }
                                        }
                                    @endphp
                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                @else
                                    <span>Sin postular</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if (isset($postulations[$vacancy->id]))
                                    @php
                                        $status = $postulations[$vacancy->id]->status->status ?? null;
                                    @endphp
                                    @if ($status === null)
                                        <a
                                            href="{{ route('portal.postulations.edit', $postulations[$vacancy->id]->id) }}">
                                            <button type="button"
                                                class="w-32 px-5 py-3 mb-2 text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                <i class="fa-regular fa-pen-to-square mr-1"></i>
                                                Editar
                                            </button>
                                        </a>
                                        <form
                                            action="{{ route('portal.postulations.destroy', $postulations[$vacancy->id]->id) }}"
                                            method="POST" class="inline-block w-full"
                                            id="deleteForm{{ $postulations[$vacancy->id]->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete({{ $postulations[$vacancy->id]->id }})"
                                                class="w-32 px-5 py-3 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                                <i class="fa-solid fa-ban mr-1"></i>
                                                Cancelar
                                            </button>
                                        </form>
                                    @else
                                        <a
                                            href="{{ route('portal.postulations.showReasons', $postulations[$vacancy->id]->id) }}">
                                            <button type="button"
                                                class="w-32 px-5 py-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                <i class="fa-solid fa-eye mr-1"></i>
                                                Visualizar razones
                                            </button>
                                        </a>
                                        <form
                                            action="{{ route('portal.postulations.destroy', $postulations[$vacancy->id]->id) }}"
                                            method="POST" class="inline-block w-full"
                                            id="deleteForm{{ $postulations[$vacancy->id]->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete({{ $postulations[$vacancy->id]->id }})"
                                                class="w-32 px-5 py-3 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                                <i class="fa-solid fa-ban mr-1"></i>
                                                Cancelar
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    @if ($hasUnlimitedApplications || $currentMonthApplications < 2)
                                        <a href="{{ route('portal.postulations.sendRequestVacancy', $vacancy->id) }}">
                                            <button type="button" id="postular-button-{{ $vacancy->id }}"
                                                class="w-32 px-5 py-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                <i class="fa-regular fa-pen-to-square mr-1"></i>
                                                Postular
                                            </button>
                                        </a>
                                    @else
                                        <button type="button" id="postular-button-{{ $vacancy->id }}"
                                            class="w-32 px-5 py-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                            onclick="showLimitReachedAlert()">
                                            <i class="fa-regular fa-pen-to-square mr-1"></i>
                                            Postular
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-4 mb-4 text-sm text-center text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-white"
            role="alert">
            No hay vacantes activas.
        </div>
    @endif

    <div class="flex justify-end mb-4">
        <button id="selected-vacancies-button"
            class="w-48 px-5 py-3 text-sm font-medium text-center text-white bg-gray-400 rounded-lg cursor-not-allowed"
            disabled onclick="submitSelectedVacancies()">
            Postular a 0 vacantes
        </button>
    </div>

    <div class="mt-4">
        {{ $vacancies->links() }}
    </div>

    @push('js')
        <script>
            function toggleButton(vacancyId) {
                const checkbox = document.getElementById('checkbox-' + vacancyId);
                const button = document.getElementById('postular-button-' + vacancyId);

                if (checkbox.checked) {
                    button.classList.add('bg-gray-400', 'cursor-not-allowed');
                    button.classList.remove('bg-blue-700', 'hover:bg-blue-800', 'dark:bg-blue-600', 'dark:hover:bg-blue-700');
                    button.disabled = true;
                } else {
                    button.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    button.classList.add('bg-blue-700', 'hover:bg-blue-800', 'dark:bg-blue-600', 'dark:hover:bg-blue-700');
                    button.disabled = false;
                }

                updateSelectedVacanciesButton();
            }

            function updateSelectedVacanciesButton() {
                const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
                const selectedCount = checkboxes.length;
                const selectedVacanciesButton = document.getElementById('selected-vacancies-button');

                selectedVacanciesButton.textContent = `Postular a ${selectedCount} vacantes`;

                if (selectedCount > 0) {
                    selectedVacanciesButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    selectedVacanciesButton.classList.add('bg-blue-700', 'hover:bg-blue-800', 'dark:bg-blue-600',
                        'dark:hover:bg-blue-700');
                    selectedVacanciesButton.disabled = false;
                } else {
                    selectedVacanciesButton.classList.add('bg-gray-400', 'cursor-not-allowed');
                    selectedVacanciesButton.classList.remove('bg-blue-700', 'hover:bg-blue-800', 'dark:bg-blue-600',
                        'dark:hover:bg-blue-700');
                    selectedVacanciesButton.disabled = true;
                }
            }

            function updatePostularButtons() {
                const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    const vacancyId = checkbox.id.replace('checkbox-', '');
                    const button = document.getElementById('postular-button-' + vacancyId);
                    if (checkbox.checked) {
                        button.classList.add('bg-gray-400', 'cursor-not-allowed');
                        button.classList.remove('bg-blue-700', 'hover:bg-blue-800', 'dark:bg-blue-600',
                            'dark:hover:bg-blue-700');
                        button.disabled = true;
                    } else {
                        button.classList.remove('bg-gray-400', 'cursor-not-allowed');
                        button.classList.add('bg-blue-700', 'hover:bg-blue-800', 'dark:bg-blue-600',
                            'dark:hover:bg-blue-700');
                        button.disabled = false;
                    }
                });
            }

            function submitSelectedVacancies() {
                const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
                const selectedVacancies = Array.from(checkboxes).map(checkbox => checkbox.id.replace('checkbox-', ''));

                fetch('{{ route('postulations.saveSelected') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            selected_vacancies: selectedVacancies
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.icon === 'warning') {
                            Swal.fire({
                                icon: data.icon,
                                title: data.title,
                                text: data.text,
                                showCancelButton: true,
                                confirmButtonColor: 'green',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Agregar datos',
                                cancelButtonText: 'Cancelar'
                            }).then((result) => {
                                if (result.isDismissed) {
                                    // Restablecer checkboxes y botones "Postular"
                                    checkboxes.forEach(checkbox => checkbox.checked = false);
                                    updateSelectedVacanciesButton();
                                    updatePostularButtons();
                                } else if (result.isConfirmed) {
                                    window.location.href = data.redirect_url;
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Bien hecho!',
                                text: data.message,
                            }).then(() => {
                                window.location.href = '{{ route('portal.postulations.index') }}';
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al guardar las vacantes seleccionadas.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            // Restablecer checkboxes y botones "Postular" en caso de error
                            checkboxes.forEach(checkbox => checkbox.checked = false);
                            updateSelectedVacanciesButton();
                            updatePostularButtons();
                        });
                    });
            }

            function confirmDelete(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Se eliminará su postulación",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'green',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, cancelar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm' + id).submit();
                    }
                })
            }

            function showLimitReachedAlert() {
                Swal.fire({
                    title: 'Límite de aplicaciones alcanzado',
                    text: "Has alcanzado el límite de aplicaciones para este mes.",
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                });
            }
        </script>
    @endpush
</x-portal-layout>
