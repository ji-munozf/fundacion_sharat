@section('title', 'Sharat - Historial de vacantes')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard'),
    ],
    [
        'name' => 'Reportes',
        'url' => route('portal.reports.index'),
    ],
    [
        'name' => 'Historial de vacantes',
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
                        <th scope="col" class="px-6 py-3">Sueldo bruto</th>
                        <th scope="col" class="px-6 py-3">Activo</th>
                        <th scope="col" class="px-6 py-3">¿Esta eliminado?</th>
                        <th scope="col" class="px-6 py-3">Creador</th>
                        <th scope="col" class="px-6 py-3">Institución</th>
                        <th scope="col" class="px-6 py-3">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vacancies as $vacancy)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">{{ $vacancy->name }}</td>
                            <td class="px-6 py-4">{{ $vacancy->job_title }}</td>
                            <td class="px-6 py-4">{{ $vacancy->description }}</td>
                            <td class="px-6 py-4">{{ $vacancy->contracting_manager }}</td>
                            <td class="px-6 py-4">{{ $vacancy->number_of_vacancies }}</td>
                            <td class="px-6 py-4">${{ number_format($vacancy->gross_salary, 0, ',', '.'),  }}</td>
                            <td class="px-6 py-4">{{ $vacancy->active ? 'Sí' : 'No' }}</td>
                            <td class="px-6 py-4">{{ $vacancy->is_eliminated ? 'Sí' : 'No' }}</td>
                            <td class="px-6 py-4">{{ $vacancy->user->name }}</td>
                            <td class="px-6 py-4">{{ $vacancy->institution->name }}</td>
                            <td class="px-6 py-4">
                                <button type="button" onclick="confirmRevertirEliminar({{ $vacancy->id }})"
                                    @if ($vacancy->is_eliminated == 0) class="px-3 py-2 text-sm font-medium text-center text-white bg-gray-500 rounded-lg dark:bg-gray-400 cursor-not-allowed"
                                        disabled
                                    @else
                                        class="px-3 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800" @endif>
                                    <i class="fa-solid fa-rotate-right mr-1"></i>
                                    Revertir eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mb-4">
            {{ $vacancies->links() }}
        </div>
        <div class="flex justify-end">
            <a href="{{ route('vacancies.export') }}">
                <button type="button"
                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    <i class="fa-solid fa-file-excel mr-1"></i>
                    Exportar a excel
                </button>
            </a>
            <form id="clean-vacancies-form" action="{{ route('clean.vacancies') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" id="clean-vacancies-button"
                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                    <i class="fa-solid fa-broom mr-1"></i>
                    Limpiar tabla vacantes
                </button>
            </form>
        </div>
    @else
        <div class="p-4 mb-4 text-sm text-center text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-white"
            role="alert">
            No hay vacantes registradas.
        </div>
    @endif

    @push('js')
        <script>
            function confirmRevertirEliminar(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esta acción revertirá la eliminación!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'green',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, revertir'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Llama a la función para revertir la eliminación
                        revertirEliminar(id);
                    }
                });
            }

            function revertirEliminar(id) {
                fetch(`/revertir-eliminar-vacancy/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Asegúrate de incluir el token CSRF
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Revertido!',
                                'La postulación ha sido revertida.',
                                'success'
                            ).then(() => {
                                location.reload(); // Recargar la página para ver los cambios
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Hubo un problema al revertir la postulación.',
                                'error'
                            );
                        }
                    });
            }

            document.getElementById('clean-vacancies-button').addEventListener('click', function() {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esta acción eliminará todas las vacantes y sus postulaciones asociadas!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'green',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('clean-vacancies-form').submit();
                    }
                });
            });
        </script>
    @endpush
</x-portal-layout>
