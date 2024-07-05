@section('title', 'Sharat - Visualizar razón')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard'),
    ],
    [
        'name' => 'Postulaciones',
        'url' => route('portal.postulations.index'),
    ],
    [
        'name' => 'Ver razones'
    ],
]">
    <div class="container mx-auto px-4">
        
    </div>

    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        <h1 class="text-xl text-center mb-4">
            Razones del porque fue {{ $status }} su postulación
        </h1>
        <div class="bg-gray-100 rounded-lg p-6 dark:bg-gray-700">
            <p class="text-black dark:text-white">{{ $reasons }}</p>
        </div>
    </div>
</x-portal-layout>