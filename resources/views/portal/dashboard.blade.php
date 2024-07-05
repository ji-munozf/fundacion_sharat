@section('title', 'Sharat - Dashboard')

<x-portal-layout>
    <div class="grid grid-cols-1 gap-6 mb-5">
        <div class="bg-white rounded-lg shadow-lg px-6 py-4 dark:bg-gray-800 flex flex-col items-center">
            <div class="flex flex-col items-center">
                <button class="flex text-sm border-2 border-transparent rounded-full cursor-default">
                    <img class="h-24 w-24 rounded-full object-cover" src="{{ auth()->user()->profile_photo_url }}"
                        alt="{{ Auth::user()->name }}" />
                </button>

                <div class="mt-4 text-center">
                    <h2 class="text-lg font-semibold">
                        Bienvenido al portal de Sharat Recruitment
                    </h2>
                    <div class="text-center text-lg">
                        <p>
                            {{ Auth::user()->name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mb-5">
        <div class="bg-white rounded-lg shadow-lg p-6 dark:bg-gray-800 flex flex-col">
            <div class="text-xl text-center font-semibold">
                <h1 class="text-lg font-semibold">
                    <i class="fa-solid fa-building-columns text-start mr-1"></i>
                    Nombre de la institución
                </h1>
            </div>
            <div class="text-center">
                <p>
                    {{ $userInstitutionName }}
                </p>
            </div>
        </div>
    </div>

    @role('Super admin|Admin')
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-5">
            <div class="bg-white rounded-lg shadow-lg px-6 py-4 dark:bg-gray-800 flex flex-col">
                <div class="text-xl text-center font-semibold">
                    <h1>
                        <i class="fa-solid fa-user text-start mr-1"></i>
                        Usuarios
                    </h1>
                </div>
                <div class="mb-2 text-m text-center font-medium text-gray-300">
                    Se han creado {{ $totalUsers }} usuarios.
                </div>
                <div class="flex justify-center mb-4">
                    <a href="{{ route('portal.users.index') }}">
                        <button type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <i class="fa-solid fa-user mr-1"></i>
                            Ir al menú usuarios
                        </button>
                    </a>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg px-6 py-4 dark:bg-gray-800 flex flex-col">
                <h1 class="text-xl text-center font-semibold mb-2">
                    <i class="fa-solid fa-user-tag text-start mr-1"></i>
                    Roles
                </h1>
                <div class="mb-2 text-m text-center font-medium text-gray-300">
                    Se han creado {{ $totalRoles }} roles
                </div>
                <div class="flex justify-center mb-4">
                    <a href="{{ route('portal.roles.index') }}">
                        <button type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <i class="fa-solid fa-user-tag mr-1"></i>
                            Ir al menú roles
                        </button>
                    </a>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg px-6 py-4 dark:bg-gray-800 flex flex-col">
                <h1 class="text-xl text-center font-semibold mb-2">
                    <i class="fa-solid fa-key text-start mr-1"></i>
                    Permisos
                </h1>
                <div class="mb-2 text-m text-center font-medium text-gray-300">
                    Se han creado {{ $totalPermissions }} permisos
                </div>
                <div class="flex justify-center mb-4">
                    <a href="{{ route('portal.permissions.index') }}">
                        <button type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <i class="fa-solid fa-key mr-1"></i>
                            Ir al menú permisos
                        </button>
                    </a>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg px-6 py-4 dark:bg-gray-800 flex flex-col">
                <h1 class="text-xl text-center font-semibold mb-2">
                    <i class="fa-solid fa-building-columns text-start mr-1"></i>
                    Instituciones
                </h1>
                <div class="mb-2 text-m text-center font-medium text-gray-300">
                    Se han creado {{ $totalInstitutions }} instituciones
                </div>
                <div class="flex justify-center mb-4">
                    <a href="{{ route('portal.institutions.index') }}">
                        <button type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <i class="fa-solid fa-building-columns mr-1"></i>
                            Ir al menú instituciones
                        </button>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-5">
            <div class="bg-white rounded-lg shadow-lg px-6 py-4 dark:bg-gray-800 flex flex-col">
                <h1 class="text-xl text-center font-semibold mb-4">
                    <i class="fa-solid fa-user-tag text-start mr-1"></i>
                    Usuarios según roles
                </h1>
                @foreach ($usersByRole as $roleName => $userCount)
                    <div class="mb-1 text-base font-medium dark:text-white">
                        {{ $roleName }}
                    </div>
                    <div class="mb-1 text-sm font-medium text-gray-300">
                        Cantidad de usuarios en el rol {{ strtolower($roleName) }}: {{ $userCount }}
                    </div>
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2 dark:bg-gray-700">
                            @php
                                $percentage = ($userCount / $totalUsers) * 100;
                            @endphp
                            <div class="bg-blue-600 h-2.5 rounded-full dark:bg-blue-500"
                                style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-blue-700 dark:text-white">{{ round($percentage) }}%</span>
                    </div>
                @endforeach
            </div>

            <div class="bg-white rounded-lg shadow-lg px-6 py-4 dark:bg-gray-800 flex flex-col">
                <h1 class="text-xl text-center font-semibold mb-4">
                    <i class="fa-solid fa-user-tag text-start mr-1"></i>
                    Usuarios según instituciones
                </h1>
                @foreach ($usersByInstitution as $institutionName => $userCount)
                    <div class="mb-1 text-base font-medium dark:text-white">
                        {{ $institutionName }}
                    </div>
                    <div class="mb-1 text-sm font-medium text-gray-300">
                        Cantidad de usuarios en la institución {{ strtolower($institutionName) }}: {{ $userCount }}
                    </div>
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2 dark:bg-gray-700">
                            @php
                                $percentage = ($userCount / $totalUsers) * 100;
                            @endphp
                            <div class="bg-blue-600 h-2.5 rounded-full dark:bg-blue-500"
                                style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-blue-700 dark:text-white">{{ round($percentage) }}%</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 mb-5">
            <div class="bg-white rounded-lg shadow-lg px-6 py-4 dark:bg-gray-800 flex flex-col">
                <h1 class="text-xl text-center font-semibold mb-2">
                    <i class="fa-solid fa-key text-start mr-1"></i>
                    Vacantes
                </h1>
                <div class="mb-2 text-m text-center font-medium text-gray-300">
                    Se han creado {{ $totalVacancies }} vacantes
                </div>
                <div class="flex justify-center mb-4">
                    <a href="{{ route('portal.vacancies.index') }}">
                        <button type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <i class="fa-solid fa-paste mr-1"></i>
                            Ir al menú vacantes
                        </button>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 mb-5">
            <div class="bg-white rounded-lg shadow-lg px-6 py-4 dark:bg-gray-800 flex flex-col">
                <h1 class="text-xl text-center font-semibold mb-4">
                    <i class="fa-solid fa-user-tag text-start mr-1"></i>
                    Vacantes según instituciones
                </h1>
                @foreach ($vacanciesByInstitution as $institutionName => $vacancyCount)
                    <div class="mb-1 text-base font-medium dark:text-white">
                        {{ $institutionName }}
                    </div>
                    <div class="mb-1 text-sm font-medium text-gray-300">
                        Cantidad de vacantes de la institución {{ strtolower($institutionName) }}: {{ $vacancyCount }}
                    </div>
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2 dark:bg-gray-700">
                            @php
                                $percentage = ($vacancyCount / $totalVacancies) * 100;
                            @endphp
                            <div class="bg-blue-600 h-2.5 rounded-full dark:bg-blue-500"
                                style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-blue-700 dark:text-white">{{ round($percentage) }}%</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endrole

    @role('Institución')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-5">
            <div class="bg-white rounded-lg shadow-lg px-6 py-4 dark:bg-gray-800 flex flex-col">
                <div class="text-xl text-center font-semibold">
                    <h1>
                        <i class="fa-solid fa-user text-start mr-1"></i>
                        Usuarios
                    </h1>
                </div>
                <div class="mb-2 text-center font-medium text-gray-300">
                    En la institución {{ strtolower($userInstitutionName) }} hay registrados
                    {{ $userInstitutionUserCount }} usuarios
                </div>
                <div class="flex justify-center mb-4">
                    <a href="{{ route('portal.users.index') }}">
                        <button type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <i class="fa-solid fa-user mr-1"></i>
                            Ir al menú usuarios
                        </button>
                    </a>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg px-6 py-4 dark:bg-gray-800 flex flex-col">
                <div class="text-xl text-center font-semibold">
                    <h1>
                        <i class="fa-solid fa-paste text-start mr-1"></i>
                        Vacantes
                    </h1>
                </div>
                <div class="mb-2 text-center font-medium text-gray-300">
                    En la institución {{ strtolower($userInstitutionName) }} han creado {{ $userInstitutionVacancyCount }}
                    vacantes
                </div>
                <div class="flex justify-center mb-4">
                    <a href="{{ route('portal.vacancies.index') }}">
                        <button type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <i class="fa-solid fa-paste mr-1"></i>
                            Ir al menú vacantes
                        </button>
                    </a>
                </div>
            </div>
        </div>
    @endrole
</x-portal-layout>
