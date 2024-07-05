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
            <div>
                <h2 class="text-3xl font-bold tracki text-center mt-4 sm:text-5xl ">Planes</h2>
                <p class="max-w-3xl mx-auto mt-4 text-xl text-center ">¡Revisa los planes que tenemos preparados para ti!
                </p>
            </div>
            <div class="mt-12 container space-y-12 lg:space-y-0 lg:grid lg:grid-cols-2 lg:gap-x-8">
                <div class="relative p-8 border border-gray-200 rounded-2xl shadow-sm flex flex-col">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold ">Gratuito</h3>
                        <p class="mt-4 flex items-baseline ">
                            <span class="text-5xl font-extrabold tracking-tight">$0</span><span
                                class="ml-1 text-xl font-semibold">/mes</span>
                        </p>
                        <p class="mt-6 ">¡Conoce las ventajas del plan gratuito!</p>
                        <ul role="list" class="mt-6 space-y-6">
                            <li class="flex"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg><span class="ml-3 ">Lorem ipsum dolor sit amet.</span>
                            </li>
                            <li class="flex"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg><span class="ml-3 ">Lorem ipsum dolor sit amet.</span>
                            </li>
                        </ul>
                    </div>
                    <button type="button"
                        class="bg-emerald-50 text-emerald-700 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium cursor-not-allowed"
                        disabled>
                        Ya tienes este plan
                    </button>
                </div>
                <div class="relative p-8  border border-gray-200 rounded-2xl shadow-sm flex flex-col">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold ">Premium</h3>
                        <p
                            class="absolute top-0 py-1.5 px-4 bg-emerald-500 text-white rounded-full text-xs font-semibold uppercase tracking-wide  transform -translate-y-1/2">
                            El más popular
                        </p>
                        <p class="mt-4 flex items-baseline ">
                            <span class="text-5xl font-extrabold tracking-tight">$12</span><span
                                class="ml-1 text-xl font-semibold">/mes</span>
                        </p>
                        <p class="mt-6 ">¡Conoce las ventajas del plan premium!</p>
                        <ul role="list" class="mt-6 space-y-6">
                            <li class="flex"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg><span class="ml-3 ">Lorem ipsum dolor sit amet.</span></li>
                            <li class="flex"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg><span class="ml-3 ">Lorem ipsum dolor sit amet.</span></li>
                            <li class="flex"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg><span class="ml-3 ">Lorem ipsum dolor sit amet.</span></li>
                            <li class="flex"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg><span class="ml-3 ">Lorem ipsum dolor sit amet.</span></li>
                            <li class="flex"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg><span class="ml-3 ">Lorem ipsum dolor sit amet.</span></li>
                        </ul>
                    </div>
                    <a class="bg-emerald-500 text-white hover:bg-emerald-600 mt-8 w-full py-3 px-6 border border-transparent rounded-md text-center font-medium flex items-center justify-center"
                        href="#">
                        <i class="fa-brands fa-whatsapp mr-2 text-xl"></i>
                        Contáctanos
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-portal-layout>
