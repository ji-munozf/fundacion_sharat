@section('title', 'Sharat - Historial de suscripciones')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard'),
    ],
    [
        'name' => 'Historial de suscripciones',
    ],
]">
    @if ($subscriptions->count())
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-4">
            <table class="w-full text-sm rtl:text-right text-center text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Usuario
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Duración del plan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Precio
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Fecha de inicio
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Fecha de término
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Estado
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subscriptions as $subscription)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $subscription->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $subscription->user->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $subscription->user->email }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $subscription->duration }}
                            </td>
                            <td class="px-6 py-4">
                                ${{ number_format($subscription->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($subscription->start_date)->format('d-m-Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($subscription->end_date)->format('d-m-Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4">
                                @if (now()->lt($subscription->end_date))
                                    <span class="text-green-500">Activo</span>
                                @else
                                    <span class="text-red-500">Expirado</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-4 mb-4 text-sm text-center text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-white"
            role="alert">
            Todavía no hay suscripciones registradas.
        </div>
    @endif
</x-portal-layout>
