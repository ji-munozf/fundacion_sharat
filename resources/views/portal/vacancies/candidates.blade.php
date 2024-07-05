@section('title', 'Sharat - Ver postulantes')

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
    ],
]">
    @if ($postulations->count())

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-4">
            <table class="w-full text-sm rtl:text-right text-center text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nombre</th>
                        <th scope="col" class="px-6 py-3">Apellidos</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Teléfono de contacto</th>
                        <th scope="col" class="px-6 py-3">Curriculum Vitae</th>
                        <th scope="col" class="px-6 py-3">Fortalezas</th>
                        <th scope="col" class="px-6 py-3">Razones</th>
                        <th scope="col" class="px-6 py-3">Vacante</th>
                        <th scope="col" class="px-6 py-3">Estado</th>
                        @if (auth()->user()->hasRole('Super admin') || auth()->user()->hasRole('Admin'))
                            <th scope="col" class="px-6 py-3">¿Está eliminado?</th>
                        @endif
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($postulations as $postulation)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">{{ $postulation->names }}</td>
                            <td class="px-6 py-4">{{ $postulation->last_names }}</td>
                            <td class="px-6 py-4">{{ $postulation->email }}</td>
                            <td class="px-6 py-4">
                                {{ substr($postulation->contact_number, 0, 4) }}
                                {{ substr($postulation->contact_number, 4) }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('portal.vacancies.downloadCV', $postulation->id) }}">
                                    <button type="button"
                                        class="px-5 py-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        <i class="fa-solid fa-download mr-1"></i>
                                        Descargar CV
                                    </button>
                                </a>
                            </td>
                            <td class="px-6 py-4">{{ $postulation->strengths }}</td>
                            <td class="px-6 py-4">{{ $postulation->reasons }}</td>
                            <td class="px-6 py-4">{{ $postulation->vacancy_name }}</td>
                            <td class="px-6 py-4">
                                @if (is_null($postulation->postulation_status))
                                    Pendiente
                                @elseif ($postulation->postulation_status)
                                    Aceptado
                                @else
                                    Rechazado
                                @endif
                            </td>
                            @if (auth()->user()->hasRole('Super admin') || auth()->user()->hasRole('Admin'))
                                <td class="px-6 py-4">
                                    {{ $postulation->is_eliminated_postulant ? 'Sí' : 'No' }}
                                </td>
                            @endif
                            <td class="px-6 py-4">
                                @if (auth()->user()->hasRole('Super admin') || auth()->user()->hasRole('Admin'))
                                    <form id="revert-form-{{ $vacancy->id }}"
                                        action="{{ route('postulation.revertDestroy', $vacancy->id) }}" method="POST">
                                        @csrf
                                        <button type="button"
                                            @if ($vacancy->is_eliminated_postulant == 0) class="px-3 py-2 text-sm font-medium text-center text-white bg-gray-500 rounded-lg dark:bg-gray-400 cursor-not-allowed"
                                            disabled
                                        @else
                                            class="px-3 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                                            onclick="confirmRevert({{ $vacancy->id }})" @endif>
                                            <i class="fa-solid fa-rotate-left mr-1"></i>
                                            Revertir eliminar postulante
                                        </button>
                                    </form>
                                @else
                                    @if (is_null($postulation->postulation_status))
                                        <a href="{{ route('postulation.showAcceptForm', $postulation->id) }}">
                                            <button type="button"
                                                @if ($vacancy->number_of_vacancies == 0) class="w-32 px-5 py-3 mb-2 text-sm font-medium text-center text-white rounded-lg focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800
                                                    bg-green-800 dark:bg-green-700 cursor-not-allowed"
                                                @else 
                                                    class="w-32 px-5 py-3 mb-2 text-sm font-medium text-center text-white rounded-lg focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800
                                                    bg-green-700 hover:bg-green-800 dark:bg-green-600 dark:hover:bg-green-700" @endif
                                                @if ($vacancy->number_of_vacancies == 0) disabled @endif>
                                                <i class="fa-solid fa-check mr-1"></i>
                                                Aceptar
                                            </button>
                                        </a>
                                        <a href="{{ route('postulation.showRejectForm', $postulation->id) }}">
                                            <button type="button"
                                                class="w-32 px-5 py-3 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                                <i class="fa-solid fa-x mr-1"></i> Rechazar
                                            </button>
                                        </a>
                                    @else
                                        <a href="{{ route('postulation.editReasonsForm', $postulation->id) }}">
                                            <button type="button"
                                                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm w-full h-12 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                <i class="fa-solid fa-edit mr-1"></i>
                                                Editar Razón
                                            </button>
                                        </a>
                                        <button type="button"
                                            class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm w-full h-12 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                            onclick="confirmDelete({{ $postulation->id }})">
                                            <i class="fa-solid fa-trash-can mr-1"></i>
                                            Eliminar postulante
                                        </button>
                                        <button type="button"
                                            class="focus:outline-none text-white bg-red-800 hover:bg-red-900 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm w-full h-12 dark:bg-red-700 dark:hover:bg-red-800 dark:focus:ring-red-900"
                                            onclick="confirmCancel({{ $postulation->id }})">
                                            <i class="fa-solid fa-ban mr-1"></i>
                                            Revertir elección
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
            Está vacante no tiene postulaciones.
        </div>
    @endif

    @push('js')
        <script>
            function confirmCancel(postulationId) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'green',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, cancelar',
                    cancelButtonText: 'No, mantener'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/postulation/check-eliminated/${postulationId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.is_eliminated) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: `No se puede revertir la elección debido a que el usuario eliminó su postulación asociada a la vacante ${data.vacancy_name.toLowerCase()}.`,
                                    });
                                } else {
                                    window.location.href = `/postulation/cancel/${postulationId}`;
                                }
                            });
                    }
                });
            }

            function confirmDelete(postulationId) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'green',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/postulation/destroy/${postulationId}`;
                    }
                })
            }

            function confirmRevert(vacancyId) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'green',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, revertir',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('revert-form-' + vacancyId).submit();
                    }
                })
            }
        </script>
    @endpush
</x-portal-layout>
