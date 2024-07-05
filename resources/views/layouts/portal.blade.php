@props([
    'breadcrumb' => [],
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon para modo claro -->
    <link rel="icon" href="{{ asset('favicon_light.png') }}" media="(prefers-color-scheme: light)">
    <!-- Favicon para modo oscuro -->
    <link rel="icon" href="{{ asset('favicon_dark.png') }}" media="(prefers-color-scheme: dark)">


    <title>@yield('title', 'Sharat Recruitment')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Flowbite head CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/6c0cf5d508.js" crossorigin="anonymous"></script>

    <!-- Sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

    @stack('css')
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900" x-data="{
    open: false,
}">

    @include('layouts.includes.nav')

    @include('layouts.includes.aside')

    <div class="p-4 sm:ml-64">

        @include('layouts.includes.breadcrumb')

        <div class="p-4 border-2 border-gray-300 border-dashed rounded-lg dark:border-gray-500 mt-14 dark:text-white">

            {{ $slot }}

        </div>
    </div>

    @stack('modals')

    @livewireScripts

    @if (session('swal'))
        <script>
            Swal.fire(@json(session('swal')))
        </script>
    @endif

    @stack('js')

    <!-- Flowite js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>
