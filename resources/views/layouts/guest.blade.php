<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'School Management System') }}</title>
        <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='7' fill='%231f2937'/%3E%3Ctext x='16' y='21.5' font-size='13' font-family='Arial' font-weight='bold' text-anchor='middle' fill='white'%3ESMS%3C/text%3E%3C/svg%3E">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="flex min-h-screen flex-col items-center justify-center bg-gray-100 px-4 py-8">
            <a href="{{ url('/') }}" class="text-center">
                <img src="{{ asset('img/logo-school.svg') }}" alt="Logo {{ config('app.name') }}" class="mx-auto h-14 w-14">
                <span class="mt-3 block text-sm font-medium text-gray-600">{{ config('app.name') }}</span>
            </a>

            <div class="mt-6 w-full overflow-hidden bg-white px-6 py-6 shadow-md sm:max-w-md sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>

        <x-ui-helpers />
        <script src="{{ asset('js/main.js') }}"></script>
    </body>
</html>
