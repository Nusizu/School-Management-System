<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'School Management System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false" class="min-h-screen">

            {{-- Latar gelap saat sidebar dibuka di layar kecil --}}
            <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
                 class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden" style="display: none;"></div>

            {{-- Sidebar --}}
            <aside class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col bg-gray-800 text-gray-300 transition-transform duration-200 lg:translate-x-0"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                <div class="flex items-center justify-between border-b border-gray-700 px-6 py-5">
                    <div>
                        <p class="text-xl font-bold tracking-wide text-white">SMS</p>
                        <p class="text-xs text-gray-400">{{ config('app.name') }}</p>
                    </div>
                    <button type="button" class="rounded-md p-1 text-gray-400 hover:text-white lg:hidden"
                            @click="sidebarOpen = false" aria-label="Tutup menu">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 space-y-1 overflow-y-auto py-4">
                    <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">Dashboard</x-sidebar-link>

                    @if (Auth::user()->isAdmin())
                        <x-sidebar-link :href="route('users.index')" :active="request()->routeIs('users.*')" icon="users">Data Pengguna</x-sidebar-link>
                    @endif

                    @unless (Auth::user()->isStudent())
                        <x-sidebar-link :href="route('students.index')" :active="request()->routeIs('students.*')" icon="student">Data Siswa</x-sidebar-link>
                        <x-sidebar-link :href="route('teachers.index')" :active="request()->routeIs('teachers.*')" icon="teacher">Data Guru</x-sidebar-link>
                        <x-sidebar-link :href="route('classes.index')" :active="request()->routeIs('classes.*')" icon="class">Data Kelas</x-sidebar-link>
                        <x-sidebar-link :href="route('subjects.index')" :active="request()->routeIs('subjects.*')" icon="subject">Mata Pelajaran</x-sidebar-link>
                    @endunless
                </nav>

                <div class="border-t border-gray-700 p-4">
                    <div class="mb-3 flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gray-600 font-semibold text-white">
                            {{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ Auth::user()->role_label }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <a href="{{ route('profile.edit') }}" class="text-gray-300 hover:text-white">Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-300 hover:text-white">Keluar</button>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- Area konten --}}
            <div class="lg:pl-64">
                <header class="sticky top-0 z-20 bg-white shadow-sm">
                    <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
                        <button type="button" class="-ml-2 rounded-md p-2 text-gray-500 hover:bg-gray-100 lg:hidden"
                                @click="sidebarOpen = true" aria-label="Buka menu">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>

                        <div class="min-w-0 flex-1">
                            @isset($header)
                                {{ $header }}
                            @endisset
                        </div>
                        <p class="hidden text-sm text-gray-500 sm:block">{{ \App\Support\Format::longDate() }}</p>
                    </div>
                </header>
                
                <x-breadcrumbs />

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>

        <x-ui-helpers />
        <script src="{{ asset('js/main.js') }}"></script>
    </body>
</html>
