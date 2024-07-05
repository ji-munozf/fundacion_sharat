@section('title', 'Sharat - Historial de datos de postulación')

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
        'name' => 'Historial de datos de postulación',
    ],
]">
    @if ($postulation_datas->count())
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-4">
            <table class="w-full text-sm rtl:text-right text-center text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nombres</th>
                        <th scope="col" class="px-6 py-3">Apellidos</th>
                        <th scope="col" class="px-6 py-3">email</th>
                        <th scope="col" class="px-6 py-3">Número de contacto</th>
                        <th scope="col" class="px-6 py-3">Curriculum vitae</th>
                        <th scope="col" class="px-6 py-3">Fortalezas</th>
                        <th scope="col" class="px-6 py-3">Razones</th>
                        <th scope="col" class="px-6 py-3">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($postulation_datas as $postulation_data)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">{{ $postulation_data->names }}</td>
                            <td class="px-6 py-4">{{ $postulation_data->last_names }}</td>
                            <td class="px-6 py-4">{{ $postulation_data->email }}</td>
                            <td class="px-6 py-4">{{ substr($postulation_data->contact_number, 0, 4) . ' ' . substr($postulation_data->contact_number, 4), }}</td>
                            <td class="px-6 py-4">{{ basename($postulation_data->curriculum_vitae) }}</td>
                            <td class="px-6 py-4">{{ $postulation_data->strengths }}</td>
                            <td class="px-6 py-4">{{ $postulation_data->reasons }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('destroy.postulation.data', $postulation_data->user_id) }}"
                                    method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)"
                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                        <i class="fa-solid fa-trash-can mr-1"></i>
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mb-4">
            {{ $postulation_datas->links() }}
        </div>
        <div class="flex justify-end">
            <a href="{{ route('postulation_users_data.export') }}">
                <button type="button"
                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    <i class="fa-solid fa-file-excel mr-1"></i>
                    Exportar a excel
                </button>
            </a>
            <form id="clean-postulation-user-data-form" action="{{ route('clean.postulation_user_data') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" id="clean-postulation-user-data-button"
                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                    <i class="fa-solid fa-broom mr-1"></i>
                    Limpiar tabla datos de postulación
                </button>
            </form>
        </div>
    @else
        <div class="p-4 mb-4 text-sm text-center text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-white"
            role="alert">
            Todavía no hay datos de postulación registradas.
        </div>
    @endif

    @push('js')
        <script>
            function confirmDelete(button) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "No podrás revertir esto",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'green',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo'
                }).then((result) => {
                    if (result.isConfirmed) {
                        button.closest('form').submit();
                    }
                });
            }

            document.getElementById('clean-postulation-user-data-button').addEventListener('click', function() {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esta acción eliminará todos los datos de postulación!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'green',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('clean-postulation-user-data-form').submit();
                    }
                });
            });
        </script>
    @endpush
</x-portal-layout>
