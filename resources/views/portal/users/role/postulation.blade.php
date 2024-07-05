@section('title', 'Sharat - Postulantes')

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
        'name' => 'Postulantes',
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
                                @if ($user->plan_id == 2)
                                    <a href="#">
                                        <button type="button"
                                            class="px-3 py-2 mr-1 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            <i class="fa-regular fa-pen-to-square mr-1"></i>
                                            Editar Suscripción
                                        </button>
                                    </a>
                                    <form id="cancelSubscriptionForm"
                                        action="{{ route('portal.users.cancelSubscription', $user) }}"
                                        class="inline-block" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" id="cancelSubscriptionButton"
                                            class="px-3 py-2 mr-1 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                            <i class="fa-solid fa-ban mr-1"></i>
                                            Cancelar Suscripción
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('portal.users.makePremiumView', $user) }}">
                                        <button type="button"
                                            class="px-3 py-2 mr-1 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            <i class="fa-regular fa-pen-to-square mr-1"></i>
                                            Hacer Premium
                                        </button>
                                    </a>
                                @endif
                                <form action="{{ route('portal.users.destroy', $user) }}" class="inline-block"
                                    method="POST" id="deleteForm{{ $user->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $user->id }})"
                                        class="px-3 py-2 mr-1 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                        <i class="fa-regular fa-trash-can mr-1"></i>
                                        Eliminar usuario
                                    </button>
                                </form>
                                <a href="{{ route('portal.users.password', $user) }}">
                                    <button type="button"
                                        class="px-3 py-2 mr-1 text-sm font-medium text-center text-white bg-yellow-700 rounded-lg hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                                        <i class="fa-solid fa-key mr-1"></i>
                                        Cambiar Password
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
            Todavía no hay usuarios postulantes registrados.
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

        <script>
            document.getElementById('cancelSubscriptionButton').addEventListener('click', function(event) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'green',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, cancelar suscripción',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('cancelSubscriptionForm').submit();
                    }
                })
            });
        </script>
    @endpush
</x-portal-layout>
