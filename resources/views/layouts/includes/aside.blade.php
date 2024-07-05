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
            'name' => 'Planes',
            'url' => route('portal.plans.index'),
            'active' => request()->routeIs('portal.plans.*'),
            'icon' => 'fa-solid fa-dollar-sign',
            'can' => ['Visualizar planes'],
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
        [
            'name' => 'Postulaciones',
            'url' => route('portal.postulations.index'),
            'active' => request()->routeIs('portal.postulations.*'),
            'icon' => 'fa-solid fa-bell',
            'can' => ['Visualizar postulación'],
        ],
        [
            'name' => 'Volver a fundación sharat',
            'url' => route('home'),
            'active' => false,
            'icon' => 'fa-solid fa-backward',
            'method' => 'POST',
        ],
        [
            'name' => 'Cerrar sesión',
            'url' => route('logout'),
            'active' => false,
            'icon' => 'fa-solid fa-right-from-bracket',
            'method' => 'POST',
        ],
    ];
@endphp

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    :class="{
        '-translate-x-full': !open,
        'transform-none': open
    }" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800 flex flex-col justify-between">
        <div>
            <ul class="space-y-2 font-medium">
                @foreach ($links as $link)
                    @canany($link['can'] ?? [null])
                        <li>
                            @if ($link['name'] === 'Cerrar sesión')
                                <a href="{{ $link['url'] }}" onclick="event.preventDefault(); confirmLogout();"
                                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <i class="{{ $link['icon'] }} text-gray-700 dark:text-white"></i>
                                    <span class="ms-3">
                                        {{ $link['name'] }}
                                    </span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            @else
                                <a href="{{ $link['url'] }}"
                                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ $link['active'] ? 'bg-gray-200 dark:bg-gray-600' : '' }}">
                                    <i class="{{ $link['icon'] }} text-gray-700 dark:text-white"></i>
                                    <span class="ms-3">
                                        {{ $link['name'] }}
                                    </span>
                                </a>
                            @endif
                        </li>
                    @endcanany
                @endforeach
            </ul>
        </div>
        <div class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between">
            <span class="text-xs text-gray-500 sm:text-center dark:text-gray-400" id="footer-year">© <span
                    id="current-year"></span> Sharat Recruitment. All Rights Reserved.</span>
        </div>
    </div>
</aside>

@push('js')
    <script>
        function confirmLogout() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Se cerrará la sesión!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'green',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>

    <script>
        document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>
@endpush
