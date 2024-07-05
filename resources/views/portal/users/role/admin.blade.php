@section('title', 'Sharat - Administradores')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard'),
    ],
    [
        'name' => 'Usuarios',
        'url' => route('portal.users.index'),
    ],
    [
        'name' => 'Administradores',
    ],
]">

    @if ($users->count())
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-4">
            <table class="w-full text-sm rtl:text-right text-center text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Rut
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Rol
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
                    @foreach ($users as $user)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">
                                {{ $user->rut }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($user->getRoleNames()->isEmpty())
                                    Sin rol asignado
                                @else
                                    {{ $user->getRoleNames()->implode(', ') }}
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->institution ? $user->institution->name : 'Sin institución' }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('portal.users.edit', $user) }}">
                                    <button type="button"
                                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                        <i class="fa-regular fa-pen-to-square mr-1"></i>
                                        Editar
                                    </button>
                                </a>
                                <form action="{{ route('portal.users.destroy', $user) }}" method="POST"
                                    class="inline-block" id="deleteForm{{ $user->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $user->id }})"
                                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        <i class="fa-regular fa-trash-can mr-1"></i>
                                        Eliminar
                                    </button>
                                </form>
                                <a href="{{ route('portal.users.password', $user) }}">
                                    <button type="button"
                                        class="focus:outline-none text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-500 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">
                                        <i class="fa-solid fa-key mr-1"></i>
                                        Cambiar password
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
            Todavía no hay usuarios administradores registrados.
        </div>
    @endif

    <div class="mt-4">
        {{ $users->links() }}
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
