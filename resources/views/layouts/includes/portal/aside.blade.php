@php
    $links = [
        [
            'name' => 'Dashboard',
            'url' => route('portal.dashboard'),
            'active' => request()->routeIs('portal.dashboard'),
            'icon' => 'fa-solid fa-gauge-high',
            'can' => ['Acceso al dashboard'],
        ],
        [
            'name' => 'Mi perfil',
            'url' => route('profile.show'),
            'active' => request()->routeIs('profile.show'),
            'icon' => 'fa-solid fa-id-card-clip',
        ],
        [
            'name' => 'Usuarios',
            'url' => route('portal.users.index'),
            'active' => request()->routeIs('portal.users.*'),
            'icon' => 'fa-solid fa-users',
            'can' => ['Visualizar usuarios'],
        ],
        [
            'name' => 'Roles',
            'url' => route('portal.roles.index'),
            'active' => request()->routeIs('portal.roles.*'),
            'icon' => 'fa-solid fa-user-tag',
            'can' => ['Visualizar roles'],
        ],
        [
            'name' => 'Permisos',
            'url' => route('portal.permissions.index'),
            'active' => request()->routeIs('portal.permissions.*'),
            'icon' => 'fa-solid fa-key',
            'can' => ['Visualizar permisos'],
        ],
        [
            'name' => 'Instituciones',
            'url' => route('portal.institutions.index'),
            'active' => request()->routeIs('portal.institutions.*'),
            'icon' => 'fa-solid fa-building-columns',
            'can' => ['Visualizar instituciones'],
        ],
        [
            'name' => 'Vacantes',
            'url' => route('portal.vacancies.index'),
            'active' => request()->routeIs('portal.vacancies.*'),
            'icon' => 'fa-solid fa-paste',
            'can' => ['Visualizar vacantes'],
        ],
    ];
@endphp

<aside
    id="logo-sidebar"class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    :class="{
        '-translate-x-full': !open,
        'transforme-none': open
    }" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bgwhite0 dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @foreach ($links as $link)
                @canany($link['can'] ?? [null])
                    <li>
                        <a href="{{ $link['url'] }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ $link['active'] ? 'bg-gray-100 dark:bg-gray-600' : '*:' }}">
                            <i class="{{ $link['icon'] }} text-gray-700 dark:text-white"></i>
                            <span class="ms-3">
                                {{ $link['name'] }}
                            </span>
                        </a>
                    </li>
                @endcanany
            @endforeach
        </ul>
    </div>
</aside>
