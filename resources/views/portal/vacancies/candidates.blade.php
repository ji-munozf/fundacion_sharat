@section('title', 'Sharat - View candidates')

<x-portal-layout :breadcrumb="[
    [
        'name' => 'Home',
        'url' => route('portal.dashboard')
    ],
    [
        'name' => 'Vacantes',
        'url' => route('portal.vacancies.index')
    ],
    [
        'name' => 'Ver candidatos',
    ],
]">
    
</x-portal-layout>