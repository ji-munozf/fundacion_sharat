@section('title', 'Sharat - Vacantes')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard'),
    ],
    [
        'name' => 'Vacantes',
    ],
]">

    <div class="flex justify-end mb-4">
        <a href="{{ route('portal.vacancies.create') }}">
            <button type="button"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <i class="fa-regular fa-plus mr-1"></i>
                Nuevo
            </button>
        </a>
    </div>

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
                            Activo
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Creador
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $vacancy->active ? 'Sí' : 'No' }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($vacancy->user->hasRole('Super admin'))
                                    Super admin: {{ $vacancy->user->name }}
                                @elseif ($vacancy->user->hasRole('Admin'))
                                    Admin: {{ $vacancy->user->name }}
                                @else
                                    {{ $vacancy->user->name }}
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                {{ $vacancy->institution->name }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('portal.vacancies.edit', $vacancy) }}">
                                    <button type="button"
                                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm w-full h-12 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                        <i class="fa-regular fa-pen-to-square mr-1"></i>
                                        Editar
                                    </button>
                                </a>
                                <form action="{{ route('portal.vacancies.destroy', $vacancy) }}" method="POST"
                                    class="inline-block w-full mb-2" id="deleteForm{{ $vacancy->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $vacancy->id }})"
                                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm w-full h-12 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        <i class="fa-regular fa-trash-can mr-1"></i>
                                        Eliminar
                                    </button>
                                </form>
                                @if ($vacancy->active && auth()->user()->can('Visualizar postulantes'))
                                    @php
                                        $newApplicationsCount = $vacancy->applications()->count();
                                    @endphp
                                    <a href="{{ route('portal.vacancies.candidates', $vacancy->id) }}">
                                        <button type="button"
                                            class="focus:outline-none text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-500 font-medium rounded-lg text-sm w-full h-12 mb-2 dark:focus:ring-yellow-900">
                                            <i class="fa-solid fa-user mr-1"></i>
                                            Ver postulantes
                                            @if ($newApplicationsCount > 0)
                                                <span
                                                    class="inline-flex items-center justify-center w-4 h-4 ms-2 text-xs font-semibold text-blue-800 bg-gray-100 rounded-full">
                                                    {{ $newApplicationsCount }}
                                                </span>
                                            @endif
                                        </button>
                                    </a>
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
            Todavía no tiene vacantes registrados.
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
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm' + id).submit();
                    }
                })
            }
        </script>
    @endpush

</x-portal-layout>
