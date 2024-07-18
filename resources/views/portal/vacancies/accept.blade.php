@section('title', 'Sharat - Aceptar a postulante')

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
        'url' => route('portal.vacancies.candidates', $vacancy->id), // Aquí pasas el parámetro 'vacancy'
    ],
    [
        'name' => 'Aceptar a postulante',
    ],
]">

    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        <h1 class="text-xl text-center text-black dark:text-white">Aceptar Postulación</h1>
        <form action="{{ route('postulation.accept', $postulation->id) }}" method="POST">
            @csrf

            <x-validation-errors class="mb-4" />

            <div class="mb-3">
                <x-label class="mb-2">Razones</x-label>
                <textarea id="razones" name="razones" rows="4"
                class="block p-2.5 w-full text-gray-900 bg-gray-50 rounded-lg border focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('reasons') }}</textarea>
            </div>
            <div class="flex justify-end">
                <a href="{{ route('portal.vacancies.candidates', $postulation->vacancy_id) }}"
                    class="btn btn-secondary">
                    <button type="submit"
                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        <i class="fa-solid fa-check mr-1"></i>
                        Aceptar
                    </button>
                </a>
            </div>
        </form>
    </div>

    @push('js')
        <script>
            document.getElementById('cancel-button').addEventListener('click', function(event) {
                event.preventDefault(); // Previene la acción por defecto del enlace
                Swal.fire({
                    title: '¿Está seguro de que desea volver?',
                    text: "Se perderá todo el progreso.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'green',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, volver',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href =
                            "{{ route('portal.vacancies.candidates', $postulation->vacancy_id) }}";
                    }
                });
            });
        </script>
    @endpush
</x-portal-layout>
