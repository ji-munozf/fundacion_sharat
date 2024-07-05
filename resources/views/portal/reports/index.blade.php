@section('title', 'Sharat - Reportes')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard'),
    ],
    [
        'name' => 'Reportes',
    ],
]">

    <div class="text-center text-xl mb-4">
        <h1 class="text-black dark:text-white">Seleccione un historial</h1>
    </div>

    <div class="flex justify-center items-center">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-4">
            <div
                class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 flex flex-col items-center text-center justify-center h-full">
                <div class="text-3xl">
                    <i class="fa-solid fa-paste"></i>
                </div>
                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Vacantes
                </h5>
                <a href="{{ route('portal.reports.vacancies') }}"
                    class="inline-flex font-medium items-center text-blue-600 hover:underline">
                    <button type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <i class="fa-regular fa-eye mr-1"></i>
                        Ir al historial de vacantes
                    </button>
                </a>
            </div>
            <div
                class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 flex flex-col items-center text-center justify-center h-full">
                <div class="text-3xl">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Postulaciones</h5>
                <a href="{{ route('portal.reports.postulations') }}"
                    class="inline-flex font-medium items-center text-blue-600 hover:underline">
                    <button type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <i class="fa-regular fa-eye mr-1"></i>
                        Ir al historial de postulaciones
                    </button>
                </a>
            </div>
            <div
                class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 flex flex-col items-center text-center">
                <div class="text-3xl">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Suscripciones</h5>
                <a href="{{ route('portal.reports.subscriptions') }}"
                    class="inline-flex font-medium items-center text-blue-600 hover:underline">
                    <button type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <i class="fa-regular fa-eye mr-1"></i>
                        Ir al historial de suscripciones
                    </button>
                </a>
            </div>
            <div
                class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 flex flex-col items-center text-center">
                <div class="text-3xl">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Datos de postulación</h5>
                <a href="{{ route('portal.reports.postulation_data') }}"
                    class="inline-flex font-medium items-center text-blue-600 hover:underline">
                    <button type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <i class="fa-regular fa-eye mr-1"></i>
                        Ir al historial de datos de postulación
                    </button>
                </a>
            </div>
        </div>
    </div>

</x-portal-layout>
