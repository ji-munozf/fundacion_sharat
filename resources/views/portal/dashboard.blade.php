@section('title', 'Sharat - Dashboard')

<x-portal-layout>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded shadow-lg px-6 py-4 dark:bg-gray-700">
            <div class="flex items-center">
                <button
                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition flex-shrink-0">
                    <img class="h-8 w-8 rounded-full object-cover" src="{{ auth()->user()->profile_photo_url }}"
                        alt="{{ Auth::user()->name }}" />
                </button>

                <div class="ml-4">
                    <h2 class="text-lg font-semibold">
                        Bienvenido {{ Auth::user()->name }}
                    </h2>

                    <form action="{{route('logout')}}" method="POST">
                        @csrf

                        <button class="text-sm hover:text-gray-300">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="bg-white rounded shadow-lg p-6 dark:bg-gray-700">
            <h2 class="text-lg font-semibold">
                Institución
            </h2>
            
        </div>
    </div>
</x-portal-layout>
