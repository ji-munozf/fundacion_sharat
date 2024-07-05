@section('title', 'Sharat - Vacancies')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard')
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
                                {{ $vacancy->user->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $vacancy->user->institution->name }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('portal.vacancies.edit', $vacancy) }}">
                                    <button type="button"
                                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                        <i class="fa-regular fa-pen-to-square mr-1"></i>
                                        Editar
                                    </button>
                                </a>
                                <form action="{{ route('portal.vacancies.destroy', $vacancy) }}" method="POST"
                                    class="inline-block" id="deleteForm{{ $vacancy->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $vacancy->id }})"
                                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        <i class="fa-regular fa-trash-can mr-1"></i>
                                        Eliminar
                                    </button>
                                </form>
                                <a href="{{ route('portal.vacancies.candidates', $vacancy) }}">
                                    <button type="button"
                                        class="focus:outline-none text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-500 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">
                                        <i class="fa-solid fa-user mr-1"></i>
                                        Ver candidatos
                                    </button>
                                </a>
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
</x-portal-layout>
