@section('title', 'Sharat - Editar premium')

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
        'url' => route('portal.users.role.postulation'),
    ],
    [
        'name' => 'Editar premium',
    ],
]">

    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        <div class="text-lg text-center text-dark dark:txt-white">
            <h1>
                Editar premium del usuario {{ $user->name }}
            </h1>
        </div>

        <form action="{{ route('portal.users.updatePremium', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <x-validation-errors class="mb-4" />

            <div class="mb-4">
                <x-label for="plan-duration" class="mb-2">Duración del plan</x-label>
                <select id="plan-duration" name="duration"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    data-monthly-price="2500" data-yearly-price="30000">
                    <option value="1" {{ $activeSubscription && $activeSubscription->duration == '1' ? 'selected' : '' }}>Mensual</option>
                    <option value="2" {{ $activeSubscription && $activeSubscription->duration == '2' ? 'selected' : '' }}>Anual</option>
                </select>
            </div>

            <div class="mb-4">
                <x-label for="plan-price" class="mb-2">Precio del plan</x-label>
                <x-input id="plan-price" type="text" class="w-full cursor-not-allowed"
                    placeholder="Seleccione un plan para ver el precio"
                    value="{{ $activeSubscription ? $activeSubscription->price : '' }}" readonly />
                <input id="plan-price-hidden" name="price" type="hidden"
                    value="{{ $activeSubscription ? $activeSubscription->price : '' }}" />
            </div>

            <div class="mb-4">
                <x-label for="file_input" class="mb-2">Comprobante transferencia</x-label>
                <input
                    class="block w-full text-sm mb-1 text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400"
                    id="file_input" name="file_input" type="file" accept=".jpg, .jpeg, .png">
                <span class="text-sm text-gray-600 dark:text-gray-400">Archivo actual:
                    {{ $activeSubscription ? $activeSubscription->bank_transfer_snapshot : 'No hay archivo' }}</span>
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Fecha de inicio</x-label>
                <x-input type="text" class="w-full cursor-not-allowed"
                    value="{{ $activeSubscription ? $activeSubscription->start_date->format('Y-m-d H:i:s') : '' }}"
                    readonly />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">Fecha de termino</x-label>
                <x-input type="text" class="w-full cursor-not-allowed"
                    value="{{ $activeSubscription ? $activeSubscription->end_date->format('Y-m-d H:i:s') : '' }}"
                    readonly />
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fa-solid fa-user-plus mr-1"></i>
                    Hacer premium
                </button>
            </div>
        </form>
    </div>

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const select = document.getElementById('plan-duration');
                const priceInput = document.getElementById('plan-price');
                const hiddenPriceInput = document.getElementById('plan-price-hidden');

                // Set initial price based on the selected duration
                if (select.value == '1') {
                    priceInput.value = new Intl.NumberFormat('es-CL', {
                        style: 'currency',
                        currency: 'CLP'
                    }).format(select.getAttribute('data-monthly-price'));
                } else if (select.value == '2') {
                    priceInput.value = new Intl.NumberFormat('es-CL', {
                        style: 'currency',
                        currency: 'CLP'
                    }).format(select.getAttribute('data-yearly-price'));
                }

                select.addEventListener('change', function() {
                    const monthlyPrice = parseInt(select.getAttribute('data-monthly-price'));
                    const yearlyPrice = parseInt(select.getAttribute('data-yearly-price'));
                    let price = 0;

                    if (select.value == '1') {
                        price = monthlyPrice;
                    } else if (select.value == '2') {
                        price = yearlyPrice;
                    }

                    priceInput.value = new Intl.NumberFormat('es-CL', {
                        style: 'currency',
                        currency: 'CLP'
                    }).format(price);

                    hiddenPriceInput.value = price; // Set the numeric value in the hidden input
                });
            });
        </script>
    @endpush
</x-portal-layout>
