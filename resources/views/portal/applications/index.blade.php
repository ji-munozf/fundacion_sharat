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
                        <th scope="col" class="px-6 py-3">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Título del puesto
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Descripción
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Gerente de contrataciones
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Número de vacantes
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Institución
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vacancies as $vacancy)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $vacancy->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $vacancy->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $vacancy->job_title }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $vacancy->description }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $vacancy->contracting_manager }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $vacancy->number_of_vacancies }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $vacancy->user->institution->name }}
                            </td>
                            <td class="px-6 py-4">
                                @if (isset($applications[$vacancy->id]))
                                    <a href="{{ route('portal.applications.edit', $applications[$vacancy->id]->id) }}">
                                        <button type="button"
                                            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm w-full h-12 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                            <i class="fa-regular fa-pen-to-square mr-1"></i>
                                            Editar
                                        </button>
                                    </a>
                                    <form action="{{ route('portal.applications.destroy', $applications[$vacancy->id]->id) }}"
                                        method="POST" class="inline-block w-full" id="deleteForm{{ $applications[$vacancy->id]->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="confirmDelete({{ $applications[$vacancy->id]->id }})"
                                            class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm w-full h-12 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                            <i class="fa-regular fa-trash-can mr-1"></i>
                                            Cancelar
                                        </button>
                                    </form>
                                @else
                                    @if ($hasUnlimitedApplications || $currentMonthApplications < 2)
                                        <a href="{{ route('portal.applications.sendRequestVacancy', $vacancy->id) }}">
                                            <button type="button"
                                                class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm w-full h-12 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                <i class="fa-regular fa-pen-to-square mr-1"></i>
                                                Postular
                                            </button>
                                        </a>
                                    @else
                                        <button type="button"
                                            class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm w-full h-12 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
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
                    text: "No podrás revertir esta acción",
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
