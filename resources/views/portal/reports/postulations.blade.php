@section('title', 'Sharat - Historial de postulaciones')

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
        'name' => 'Historial de postulaciones',
    ],
]">
    @if ($postulations->count())
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-4">
            <table class="w-full text-sm rtl:text-right text-center text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nombres</th>
                        <th scope="col" class="px-6 py-3">Apellidos</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Teléfono de contacto</th>
                        <th scope="col" class="px-6 py-3">Curriculum Vitae</th>
                        <th scope="col" class="px-6 py-3">Fortalezas</th>
                        <th scope="col" class="px-6 py-3">Razones</th>
                        <th scope="col" class="px-6 py-3">¿Está eliminado?</th>
                        <th scope="col" class="px-6 py-3">Nombre de la vacante</th>
                        <th scope="col" class="px-6 py-3">Título del puesto</th>
                        <th scope="col" class="px-6 py-3">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($postulations as $postulation)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">{{ $postulation->names }}</td>
                            <td class="px-6 py-4">{{ $postulation->last_names }}</td>
                            <td class="px-6 py-4">{{ $postulation->email }}</td>
                            <td class="px-6 py-4">{{ substr($postulation->contact_number, 0, 4) }}
                                {{ substr($postulation->contact_number, 4) }}</td>
                            <td class="px-6 py-4">
                                {{ basename($postulation->curriculum_vitae) }}
                            </td>
                            <td class="px-6 py-4">{{ $postulation->strengths }}</td>
                            <td class="px-6 py-4">{{ $postulation->reasons }}</td>
                            <td class="px-6 py-4">{{ $postulation->is_eliminated ? 'Sí' : 'No' }}</td>
                            <td class="px-6 py-4">{{ $postulation->vacancy->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $postulation->vacancy->job_title ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <button type="button" onclick="confirmRevertirEliminar({{ $postulation->id }})"
                                    @if ($postulation->is_eliminated == 0) class="px-3 py-2 text-sm font-medium text-center text-white bg-gray-500 rounded-lg dark:bg-gray-400 cursor-not-allowed"
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
            {{ $postulations->links() }}
        </div>
        <div class="flex justify-end">
            <a href="{{ route('postulations.export') }}">
                <button type="button"
                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    <i class="fa-solid fa-file-excel mr-1"></i>
                    Exportar a excel
                </button>
            </a>
            <form id="clean-postulations-form" action="{{ route('clean.postulations') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" id="clean-postulations-button"
                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                    <i class="fa-solid fa-broom mr-1"></i>
                    Limpiar tabla postulaciones
                </button>
            </form>
        </div>
    @else
        <div class="p-4 mb-4 text-sm text-center text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-white"
            role="alert">
            No hay postulaciones registradas.
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
                fetch(`/revertir-eliminar-postulation/${id}`, {
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

            document.getElementById('clean-postulations-button').addEventListener('click', function() {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esta acción eliminará todas las postulaciones!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'green',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('clean-postulations-form').submit();
                    }
                });
            });
        </script>
    @endpush
</x-portal-layout>
