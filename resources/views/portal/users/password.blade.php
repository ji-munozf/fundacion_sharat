@section('title', 'Sharat - Cambiar contraseña')

<x-portal-layout :breadcrumb="$breadcrumb_pass">
    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        <div class="mb-4 text-center text-lg">
            Cambiar contraseña de: {{ $user->name }}
        </div>
        
        <form action="{{ route('portal.users.updatePass', $user) }}" method="POST"
            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

            @csrf

            <x-validation-errors class="mb-4" />

            <div class="mb-4">
                <x-label class="mb-2">
                    Nueva contraseña
                </x-label>

                <x-input name='password' type="password" class="w-full" placeholder="Ingrese la contraseña del usuario" />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">
                    Confirmar contraseña
                </x-label>

                <x-input name='confirm_password' type="password" class="w-full"
                    placeholder="Ingrese nuevamente la contraseña del usuario" />
            </div>

            <div class="flex justify-end">
                <x-button>
                    Cambiar contraseña
                </x-button>
            </div>

        </form>
    </div>
</x-portal-layout>
