@section('title', 'Sharat - Planes')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard'),
    ],
    [
        'name' => 'Planes',
    ],
]">

    <div class="bg-white rounded-lg shadow-lg px-6 py-4 dark:bg-gray-800 flex flex-col items-center">
        <div class="mb-4">
            <h2 class="text-3xl font-bold tracking-tight text-center mt-4 sm:text-5xl">Planes</h2>
            <p class="max-w-3xl mx-auto mt-4 text-xl text-center">¡Revisa los planes que tenemos preparados para ti!</p>

            <!-- Toggle Switch -->
            <div class="flex justify-center items-center mt-6">
                <span class="mr-2">Mensual</span>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="priceToggle" class="sr-only peer">
                    <div
                        class="relative w-11 h-6 bg-emerald-500 dark:bg-emerald-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all">
                    </div>
                </label>
                <span class="ml-2">Anual</span>
            </div>

            <div class="mt-12 container space-y-12 lg:space-y-0 lg:grid lg:grid-cols-2 lg:gap-x-8">
                @foreach ($plans as $plan)
                    <div class="relative p-8 border border-gray-200 rounded-2xl shadow-sm flex flex-col">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold">{{ $plan->name }}</h3>
                            <p class="mt-4 flex items-baseline">
                            <div class="mb-2">
                                <span id="price-{{ $plan->id }}"
                                    class="text-5xl font-extrabold tracking-tight">${{ number_format($plan->monthly_price, 0, ',', '.') }}</span><span
                                    id="duration-{{ $plan->id }}" class="ml-1 text-xl font-semibold">/mes</span>
                            </div>
                            </p>
                            <p class="mt-6">¡Conoce las ventajas del plan {{ strtolower($plan->name) }}!</p>
                            <ul role="list" class="mt-6 space-y-6">
                                @if ($plan->name === 'Gratuito')
                                    <li class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span class="ml-3">Postulaciones limitadas (2 por mes).</span>
                                    </li>
                                    <li class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span class="ml-3">Ingresar sus datos de postulación en cada vacante.</span>
                                    </li>
                                @elseif ($plan->name === 'Premium')
                                    <li class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span class="ml-3">Postulaciones ilimitadas.</span>
                                    </li>
                                    <li class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span class="ml-3">Guardar sus datos de postulación.</span>
                                    </li>
                                    <li class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span class="ml-3">Autocompletado de sus datos al postular.</span>
                                    </li>
                                    <li class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span class="ml-3">Seleccionar y postular a varias vacantes.</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        @if ($plan->name === 'Gratuito' && isset($currentPlan) && $currentPlan->name === 'Gratuito')
                            <button type="button"
                                class="bg-emerald-50 text-emerald-700 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium cursor-not-allowed"
                                disabled>
                                Ya tienes este plan
                            </button>
                        @elseif (isset($currentPlan) && $currentPlan->name === 'Premium' && $plan->name === 'Gratuito')
                            <button type="button"
                                class="bg-emerald-500 text-white mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium cursor-not-allowed"
                                disabled>
                                Plan gratuito
                            </button>
                        @elseif (isset($currentPlan) && $currentPlan->name === 'Premium' && $plan->name !== 'Gratuito')
                            <button type="button"
                                class="bg-emerald-50 text-emerald-700 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium cursor-not-allowed"
                                disabled>
                                Ya tienes este plan
                            </button>
                        @else
                            <a class="bg-emerald-500 text-white hover:bg-emerald-600 mt-8 w-full py-3 px-6 border border-transparent rounded-md text-center font-medium flex items-center justify-center"
                                href="#">
                                <i class="fa-brands fa-whatsapp mr-2 text-xl"></i>
                                Contáctanos
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.getElementById('priceToggle').addEventListener('change', function() {
                var isChecked = this.checked;
                @foreach ($plans as $plan)
                    var priceElement = document.getElementById('price-{{ $plan->id }}');
                    var durationElement = document.getElementById('duration-{{ $plan->id }}');
                    if (isChecked) {
                        priceElement.textContent = '${{ number_format($plan->yearly_price, 0, ',', '.') }}';
                        durationElement.textContent = '/año';
                    } else {
                        priceElement.textContent = '${{ number_format($plan->monthly_price, 0, ',', '.') }}';
                        durationElement.textContent = '/mes';
                    }
                @endforeach
            });
        </script>
    @endpush
</x-portal-layout>
