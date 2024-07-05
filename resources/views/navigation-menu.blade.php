<nav class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="{{ route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <picture>
                <!-- Imagen para modo oscuro -->
                <source srcset="{{ asset('favicon_dark.png') }}" media="(prefers-color-scheme: dark)">
                <!-- Imagen por defecto para modo claro -->
                <img src="{{ asset('favicon_light.png') }}" class="h-12" alt="Logo de Fundación Sharat" /> <!-- Ajusta la clase aquí -->
            </picture>
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Fundación Sharat</span>
        </a>        
        <button data-collapse-toggle="navbar-solid-bg" type="button"
            class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
            aria-controls="navbar-solid-bg" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-solid-bg">
            <ul
                class="flex flex-col font-medium mt-4 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-transparent dark:bg-gray-800 md:dark:bg-transparent dark:border-gray-700">
                <li>
                    <a href="{{ route('home') }}"
                        class="{{ Request::is('/') ? 'text-blue-700 dark:text-blue-500' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 md:hover:text-blue-700 md:dark:hover:text-blue-500 md:dark:hover:bg-transparent' }} block py-2 px-3 md:p-0 rounded md:bg-transparent"
                        aria-current="page">
                        Inicio
                    </a>
                </li>
                @if (Route::has('login'))
                    @auth
                        <li>
                            <a href="{{ route('portal.dashboard') }}"
                                class="{{ Request::is('dashboard') ? 'text-blue-700 dark:text-blue-500' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 md:hover:text-blue-700 md:dark:hover:text-blue-500 md:dark:hover:bg-transparent' }} block py-2 px-3 md:p-0 rounded md:bg-transparent">
                                Portal Recruitment
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 md:hover:text-blue-700 md:dark:hover:text-blue-500 md:dark:hover:bg-transparent block py-2 px-3 md:p-0 rounded md:bg-transparent">
                                    Cerrar sesión
                                </a>
                            </form>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}"
                                class="{{ Request::is('login') ? 'text-blue-700 dark:text-blue-500' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 md:hover:text-blue-700 md:dark:hover:text-blue-500 md:dark:hover:bg-transparent' }} block py-2 px-3 md:p-0 rounded md:bg-transparent">
                                Iniciar sesión
                            </a>
                        </li>
                        @if (Route::has('register'))
                            <li>
                                <a href="{{ route('register') }}"
                                    class="{{ Request::is('register') ? 'text-blue-700 dark:text-blue-500' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 md:hover:text-blue-700 md:dark:hover:text-blue-500 md:dark:hover:bg-transparent' }} block py-2 px-3 md:p-0 rounded md:bg-transparent">
                                    Registrarse
                                </a>
                            </li>
                        @endif
                    @endauth
                @endif
            </ul>
        </div>
    </div>
</nav>
