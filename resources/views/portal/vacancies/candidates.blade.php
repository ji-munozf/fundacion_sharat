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
    @if ($applications->count())

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-4">
            <table class="w-full text-sm rtl:text-right text-center text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Apellidos
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Teléfono de contacto
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Curriculum Vitae
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Fortalezas
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Razones
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Vacante
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applications as $application)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $application->names }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $application->last_names }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $application->email }}
                            </td>
                            <td class="px-6 py-4">
                                {{ substr($application->contact_number, 0, 4) }} {{ substr($application->contact_number, 4) }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('portal.vacancies.downloadCV', $application->id) }}">
                                    <button type="button"
                                    class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm w-full h-12 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <i class="fa-solid fa-download mr-1"></i>
                                        Descargar CV
                                    </button>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                {{ $application->strengths }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $application->reasons }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $application->vacancy_title }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="#">
                                    <button type="button"
                                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm w-full h-12 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                        <i class="fa-solid fa-check mr-1"></i>
                                        Aceptar
                                    </button>
                                </a>
                                <a href="#">
                                    <button type="button"
                                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm w-full h-12 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                    <i class="fa-solid fa-x mr-1"></i>
                                        Rechazar
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
            Está vacante no tiene postulaciones.
        </div>
    @endif


</x-portal-layout>
