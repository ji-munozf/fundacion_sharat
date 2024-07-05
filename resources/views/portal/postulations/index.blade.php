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
                                        $status = $postulations[$vacancy->id]->status;
                                        $statusText = 'Pendiente';
                                        $statusClass = 'font-semibold text-yellow-500'; // Clase por defecto para "Pendiente"
                                        if ($status) {
                                            if ($status->status) {
                                                $statusText = 'Aceptado';
                                                $statusClass = 'font-semibold text-green-500'; // Clase para "Aceptado"
                                            } else {
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
                                        $status = $postulations[$vacancy->id]->status;
                                    @endphp
                                    @if ($status)
                                        <a
                                            href="{{ route('portal.postulations.showReasons', $postulations[$vacancy->id]->id) }}">
                                            <button type="button"
                                                class="w-32 px-5 py-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                <i class="fa-solid fa-eye mr-1"></i>
                                                Visualizar razón
                                            </button>
                                        </a>
                                    @else
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
                                    @endif
                                @else
                                    @if ($hasUnlimitedApplications || $currentMonthApplications < 2)
                                        <a href="{{ route('portal.postulations.sendRequestVacancy', $vacancy->id) }}">
                                            <button type="button"
                                                class="w-32 px-5 py-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                <i class="fa-regular fa-pen-to-square mr-1"></i>
                                                Postular
                                            </button>
                                        </a>
                                    @else
                                        <button type="button"
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

    <div class="mt-4">
        {{ $vacancies->links() }}
    </div>

    @push('js')
        <script>
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
        </script>
    @endpush

    @push('js')
        <script>
            function showLimitReachedAlert() {
                Swal.fire({
                    icon: 'error',
                    title: 'Límite de postulaciones alcanzado',
                    text: 'Solo puedes postularte a dos vacantes por mes.',
                });
            }
        </script>
    @endpush

</x-portal-layout>
